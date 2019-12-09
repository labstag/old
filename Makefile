.DEFAULT_GOAL := help
EXEC_PHP = ./bin/
EXEC_SYMFONY = $(EXEC_PHP)console
PHPDOCUMENTORURL = https://github.com/phpDocumentor/phpDocumentor2/releases/download/v2.9.0/phpDocumentor.phar
PHPDOCUMENTORFILE = phpDocumentor.phar
	
.PHONY: help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
	
.PHONY: commit
commit: ## Commit data
	npm run commit
	
.PHONY: install
install: ## install
	composer install
	npm install
	
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
