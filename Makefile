.DEFAULT_GOAL := help
EXEC_PHP = ./bin/
PHPDOCUMENTORURL = https://github.com/phpDocumentor/phpDocumentor2/releases/download/v2.9.0/phpDocumentor.phar
PHPDOCUMENTORFILE = phpDocumentor.phar
CONTAINER = labstag-phpfpm7
ARGS=$(filter-out $@,$(MAKECMDGOALS))
	
.PHONY: help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
	
.PHONY: commit
commit: ## Commit data
	npm run commit
	
.PHONY: install
install: ## install
	make build -i
	make start -i
	npm install
	docker exec $(CONTAINER) composer install
	make stop -i

.PHONY: build
build: ## build docker
	docker-compose build

.PHONY: start
start: ## Start docker
	docker-compose up -d

.PHONY: restart
restart: ## restart docker
	docker-compose stop
	docker-compose up -d

.PHONY: logs
logs: ## logs docker
	docker-compose logs -f

.PHONY: composer-update
composer-update: ## COMPOSER update
	docker exec $(CONTAINER) composer update

.PHONY: composer-validate
composer-validate: ## COMPOSER validate
	docker exec $(CONTAINER) composer validate

.PHONY: ssh
ssh: ## SSH
	docker exec -it $(CONTAINER) /bin/bash

.PHONY: stop
stop: ## Stop docker
	docker-compose stop

.PHONY: docker-recreate
docker-recreate: ## RECREATE docker
	make docker-stop -i
	make docker-start -i
	
.PHONY: licenses
licenses: ## Show licenses
	make licensesPHP -i
	make licensesJSCSS -i
	
.PHONY: licensesPHP
licensesPHP: ## Show licenses PHP
	composer licenses
	
.PHONY: licensesJSCSS
licensesJSCSS: ## Show licenses JS / CSS
	npm run licenses
	
.PHONY: phpdoc
phpdoc: phpdoc.dist.xml ## PHPDoc
	rm -rf public/docs/api 
	rm -rf output
	wget -nc $(PHPDOCUMENTORURL)
	php phpDocumentor.phar
	rm -rf output
	
.PHONY: watch-localhost
watch-localhost:
	export NODE_ENV=localhost && npm run watch
	
.PHONY: phpcsfixer
phpcsfixer:
	composer php-cs-fixer
	
.PHONY: phpcbf
phpcbf:
	composer phpcbf
	
.PHONY: phpmd
phpmd:
	composer phpmd
	
.PHONY: phpcs
phpcs:
	composer phpcs
	
.PHONY: phpstan
phpstan:
	composer phpstan
	
.PHONY: phpcpd
phpcpd:
	composer phpcpd
	
.PHONY: phpmnd
phpmnd:
	composer phpmnd
	
.PHONY: twigcs
twigcs:
	composer twigcs

.PHONY: audit
audit: ## 
	make phpcsfixer -i
	make phpcbf -i
	make phpmd -i
	make phpcs -i
	make phpstan -i
	make phpcpd -i
	make phpmnd -i
	make twigcs -i

.PHONY: phpunit
phpunit: ## PHPUnit
	make start -i
	docker exec $(CONTAINER) php bin/console doctrine:migrations:migrate -n
	docker exec $(CONTAINER) php bin/console doctrine:fixtures:load -n
	docker exec $(CONTAINER) composer phpunit
	make stop -i
