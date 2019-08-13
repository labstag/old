.DEFAULT_GOAL := help
EXEC_PHP = ./bin/
EXEC_SYMFONY = $(EXEC_PHP)console
MAKE = make
NPM = npm run
COMPOSER = composer
PHPDOCUMENTORURL = https://github.com/phpDocumentor/phpDocumentor2/releases/download/v2.9.0/phpDocumentor.phar
PHPDOCUMENTORFILE = phpDocumentor.phar

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

licenses: ## Show licenses
	$(MAKE) licensesPHP
	$(MAKE) licensesJSCSS

licensesPHP: ## Show licenses PHP
	$(COMPOSER) licenses

licensesJSCSS: ## Show licenses JS / CSS
	$(NPM) licenses

phpdoc: phpdoc.dist.xml ## PHPDoc
	rm -rf public/docs/api 
	rm -rf output
	wget -nc $(PHPDOCUMENTORURL)
	php phpDocumentor.phar
	rm -rf output
phpcsfixer:
	composer php-cs-fixer
phpcbf:
	composer phpcbf
phpmd:
	composer phpmd
phpcs:
	composer phpcs
phpstan:
	composer phpstan
phpcpd:
	composer phpcpd
phpmnd:
	composer phpmnd
twigcs:
	composer twigcs
audit: ## 
	$(MAKE) phpcsfixer -i
	$(MAKE) phpcbf -i
	$(MAKE) phpmd -i
	$(MAKE) phpcs -i
	$(MAKE) phpstan -i
	$(MAKE) phpcpd -i
	$(MAKE) phpmnd -i
	$(MAKE) twigcs -i
