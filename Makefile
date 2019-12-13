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


.PHONY: install-dev
install-dev: ## install DEV
	make build -i
	make start -i
	make composer-install-dev -i
	npm install
	make bdd-dev -i
	make migrate -i
	make stop -i

.PHONY: install-prod
install-prod: ## install PROD
	make build -i
	make start -i
	make composer-install-prod -i
	npm install
	make bdd-dev -i
	make migrate -i
	make stop -i

.PHONY: migrate
migrate: ## migrate database
	docker exec $(CONTAINER) php bin/console doctrine:migrations:migrate -n

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

.PHONY: composer-install-dev
composer-install-dev: ## COMPOSER install DEV
	docker exec $(CONTAINER) composer install

.PHONY: composer-install-prod
composer-install-prod: ## COMPOSER install PROD
	docker exec $(CONTAINER) composer install --no-dev

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
watch-localhost: ## WEBPACK DEV
	export NODE_ENV=localhost && npm run watch
	
.PHONY: phpcsfixer
phpcsfixer: ## PHPCSFIXER
	composer php-cs-fixer
	
.PHONY: phpcbf
phpcbf: ## PHPCBF
	composer phpcbf
	
.PHONY: phpmd
phpmd: ## PHPMD
	composer phpmd
	
.PHONY: phpcs
phpcs: ## PHPCS
	composer phpcs
	
.PHONY: phpstan
phpstan: ## PHPSTAN
	composer phpstan
	
.PHONY: phpcpd
phpcpd: ## PHPCPD
	composer phpcpd
	
.PHONY: phpmnd
phpmnd: ## PHPMND
	composer phpmnd
	
.PHONY: twigcs
twigcs: ## TWIGCS
	composer twigcs

.PHONY: audit
audit: ## AUDIT CODE PHP
	make phpcsfixer -i
	make phpcbf -i
	make phpmd -i
	make phpcs -i
	make phpstan -i
	make phpcpd -i
	make phpmnd -i
	make twigcs -i

.PHONY: fixtures
fixtures: ## PHPUnit
	docker exec $(CONTAINER) php bin/console doctrine:fixtures:load -n

.PHONY: phpunit
phpunit: ## PHPUnit
	docker exec $(CONTAINER) composer phpunit

.PHONY: bdd-dev
bdd-dev: ## Install BDD DEV
	docker exec $(CONTAINER) cp .env.dist .env
