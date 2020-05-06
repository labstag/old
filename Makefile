USER              := $(shell id -u)
GROUP             := $(shell id -g)
.DEFAULT_GOAL     := help
EXEC_PHP          := ./bin/
PHPDOCUMENTORURL  := https://github.com/phpDocumentor/phpDocumentor2/releases/download/v2.9.0/phpDocumentor.phar
PHPDOCUMENTORFILE := phpDocumentor.phar
PHPFPM            := labstagapi_phpfpm
MARIADB           := labstagapi_mariadb
APACHE            := labstagapi_apache
STACK             := labstagapi
NETWORK           := proxynetwork
PHPFPMFULLNAME    := $(PHPFPM).1.$$(docker service ps -f 'name=$(PHPFPM)' $(PHPFPM) -q --no-trunc | head -n1)
MARIADBFULLNAME   := $(MARIADB).1.$$(docker service ps -f 'name=$(MARIADB)' $(MARIADB) -q --no-trunc | head -n1)
APACHEFULLNAME    := $(APACHE).1.$$(docker service ps -f 'name=$(APACHE)' $(APACHE) -q --no-trunc | head -n1)
ARGS              := $(filter-out $@,$(MAKECMDGOALS))

.PHONY: help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: commit
commit: node_modules ## Commit data
	@npm run commit

.PHONY: docker-image-pull
docker-image-pull: ## Get docker image
	docker image pull koromerzhin/vuejs:4.2.2
	docker image pull mariadb:10.5.1
	docker image pull httpd
	docker image pull koromerzhin/phpfpm:7.4

.PHONY: fullinstall
fullinstall: node_modules ## ful install
	@make docker-image-pull
	@make install -i
	@make setenv -i
	@make sleep -i
	@make install-dev -i

.PHONY: create-network
create-network: ## create network
	docker network create --driver=overlay $(NETWORK)

.PHONY: cache-clear
cache-clear: ## Cache clear
	docker exec $(PHPFPMFULLNAME) php bin/console c:c

.PHONY: deploy
deploy: ## deploy
	docker stack deploy -c docker-compose.yml $(STACK)

.PHONY: update
update: ## update DEPEDENCIES
	npm update
	@make composer-update -i
	@make npm-update -i

.PHONY: pull
pull: node_modules ## Update repository
	@make composer-install -i

.PHONY: install
install: node_modules ## install DEV
	@make docker-image-pull -i
	@make deploy -i

node_modules:
	npm install

.PHONY: showstack
showstack: ## Show stack
	@docker stack ps $(STACK)
	@docker service ls

.PHONY: install-dev
install-dev: ## continue-install-dev
	@make composer-install -i
	@make migrate -i
	@make fixtures -i

.PHONY: install-prod
install-prod: ## continue-install-prod
	@make composer-install -i
	docker exec $(PHPFPMFULLNAME) sed -i 's/APP_ENV=dev/APP_ENV=prod/g'   .env
	@make migrate -i

.PHONY: setenv
setenv: ## Install .env
	cp apps/.env.dist apps/.env

.PHONY: migrate
migrate: ## migrate database
	docker exec $(PHPFPMFULLNAME) php bin/console doctrine:migrations:migrate -n

.PHONY: logs
logs: ## logs docker
	docker service logs -f --tail 100 --raw $(STACK)

.PHONY: logs-mariadb
logs-mariadb: ## logs docker mariadb
	docker service logs -f --tail 100 --raw $(MARIADBFULLNAME)

.PHONY: composer-install
composer-install: ## COMPOSER install
	docker exec $(PHPFPMFULLNAME) composer install

.PHONY: composer-update
composer-update: ## COMPOSER update
	docker exec $(PHPFPMFULLNAME) composer update

.PHONY: composer-validate
composer-validate: ## COMPOSER validate
	docker exec $(PHPFPMFULLNAME) composer validate

.PHONY: ssh
ssh: ## ssh
	docker exec -ti $(PHPFPMFULLNAME) /bin/bash

.PHONY: stop
stop: ## Stop docker
	docker stack rm $(STACK)

.PHONY: docker-recreate
docker-recreate: ## RECREATE docker
	@make docker-stop -i
	@make docker-start -i

.PHONY: licenses
licenses: ## Show licenses
	@make licensesPHP -i
	@make licensesJSCSS -i

.PHONY: licensesPHP
licensesPHP: ## Show licenses PHP
	docker exec $(PHPFPMFULLNAME) composer licenses

.PHONY: licensesJSCSS
licensesJSCSS: node_modules ## Show licenses JS / CSS
	@npm run licenses

.PHONY: fixtures
fixtures: ## PHPUnit
	docker exec $(PHPFPMFULLNAME) php bin/console doctrine:fixtures:load -n

.PHONY: git-author
git-author: ## git author
	@git log --pretty=%an\ \<%ae\>|sort|uniq -c

.PHONY: git-check
git-check: ## CHECK git
	@git gc
	@git prune
	@git fetch

.PHONY: sleep
sleep: ## sleep
	sleep 90


.PHONY: generate-ssh-key
generate-ssh-key: ## generate ssh key
	mkdir -p apps/config/jwt
	openssl genpkey -out apps/config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
	openssl pkey -in apps/config/jwt/private.pem -out apps/config/jwt/public.pem -pubout
