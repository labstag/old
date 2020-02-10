user := $(shell id -u)
group := $(shell id -g)
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

.PHONY: update-dev
update-dev: ## update DEV
	make composer-update -i
	make npm-update -i

.PHONY: install-dev
install-dev: ## install DEV
	make build -i
	make start -i
	make composer-install-dev -i
	make npm-install -i
	make bdd-dev -i
	make migrate -i
	make fixtures -i
	docker exec $(CONTAINER) npm run dev
	make stop -i


.PHONY: npm-doctor
npm-doctor: ## doctor NPM
	docker exec $(CONTAINER) npm doctor

.PHONY: npm-clean-install
npm-clean-install: ## install PROD
	docker exec $(CONTAINER) npm clean-install

.PHONY: npm-install
npm-install: ## npm install PROD
	docker exec $(CONTAINER) npm install

.PHONY: npm-update
npm-update: ## npm update PROD
	docker exec $(CONTAINER) npm update

.PHONY: install-prod
install-prod: ## install PROD
	make build -i
	make start -i
	make composer-install-prod -i
	npm install
	make bdd-dev -i
	make migrate -i
	docker exec $(CONTAINER) npm run build
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

.PHONY: logs-mariadb
logs-mariadb: ## logs docker mariadb
	docker-compose logs -f labstag-mariadb

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
	docker-compose exec $(CONTAINER) /bin/bash

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
	docker exec $(CONTAINER) composer licenses
	
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
	docker exec $(CONTAINER) composer php-cs-fixer
	
.PHONY: phpcbf
phpcbf: ## PHPCBF
	docker exec $(CONTAINER) composer phpcbf
	
.PHONY: phpmd
phpmd: ## PHPMD
	docker exec $(CONTAINER) composer phpmd
	
.PHONY: phpcs
phpcs: ## PHPCS
	docker exec $(CONTAINER) composer phpcs
	
.PHONY: phpstan
phpstan: ## PHPSTAN
	docker exec $(CONTAINER) composer phpstan
	
.PHONY: phpcpd
phpcpd: ## PHPCPD
	docker exec $(CONTAINER) composer phpcpd
	
.PHONY: phpmnd
phpmnd: ## PHPMND
	docker exec $(CONTAINER) composer phpmnd
	
.PHONY: twigcs
twigcs: ## TWIGCS
	docker exec $(CONTAINER) composer twigcs

.PHONY: fix
fix: ## FIX CODE PHP
	make phpcsfixer -i
	make phpcbf -i

.PHONY: fixaudit
fixaudit: ## Fix and audit file
	make fix -i
	make audit -i

.PHONY: audit
audit: ## AUDIT CODE PHP
	make phpmd -i
	make phpcs -i
	make phpstan -i
	make phpcpd -i
	make phpmnd -i
	make twigcs -i

.PHONY: fixtures
fixtures: ## PHPUnit
	docker exec $(CONTAINER) php bin/console doctrine:fixtures:load -n

.PHONY: tests
tests: ## tests
	make phpunit -i

.PHONY: phpunit
phpunit: ## PHPUnit
	docker exec $(CONTAINER) composer phpunit

.PHONY: bdd-dev
bdd-dev: ## Install BDD DEV
	docker exec $(CONTAINER) cp .env.dist .env

.PHONY: watch
watch: ## watch JS / CSS DEV
	docker exec $(CONTAINER) npm run watch

.PHONY: create-asset
create-asset: ## create ASSET
	docker exec $(CONTAINER) npm run dev

.PHONY: git-author
git-author: ## git author
	@git log --pretty=%an\ \<%ae\>|sort|uniq -c
