@echo off
chcp 65001
mkdir audit
IF "%1" == "" (
   audit help
) ELSE IF "%1" == "help" (
   @echo "Voici la liste des scripts utilisé :"
   @echo "------------------------------------"
   @echo "phpcsfixer : PHP-CS-FIXER permet de fixer le code PHP à partir d'un standard"
   @echo "https://github.com/FriendsOfPHP/PHP-CS-Fixer";
   @echo "phpcbf     : PHPCBF fixe le code PHP à partir d'un standard (pour plus de lisibilité par rapport à PHP-CS-FIXER)"
   @echo "https://github.com/squizlabs/PHP_CodeSniffer";
   @echo "phpmd      : PHPMD  indique quand le code PHP contient des erreurs de syntaxes ou des erreurs"
   @echo "https://github.com/phpmd/phpmd";
   @echo "phpcs      : PHPCS indique les erruers de code non corrigé par PHPCSFIXER + PHPCBF"
   @echo "https://github.com/squizlabs/PHP_CodeSniffer";
   @echo "phpstan    : PHPSTAN regarde si le code PHP ne peux pas être optimisé"
   @echo "https://github.com/phpstan/phpstan";
   @echo "phpmig     : PHPMIG permet de voir si le code PHP est compatible avec PHP 7.3"
   @echo "https://github.com/monque/PHP-Migration";
   @echo "phpcpd     : PHPCPD verifie si il n'y a pas de copier / coller"
   @echo "https://github.com/sebastianbergmann/phpcpd";
   @echo "phpmnd     : PHPMND Si des chiffres sont utilisé dans le code PHP, il est conseillé d'utiliser des constantes"
   @echo "https://github.com/povils/phpmnd";
   @echo "./audit => lance toute les commandes"
   @echo "./audit phpcpd => lance toute la commande phpcpd"
) ELSE IF "%1" == "fix" (
   audit phpcsfixer
   audit phpcbf
) ELSE IF "%1" == "next" (
   audit phpmd
   audit phpcs
   audit phpstan
   audit phpmig
   audit phpcpd
   audit phpmnd
) ELSE IF "%1" == "phpcsfixer" (
   @echo "phpcsfixer"
   composer php-cs-fixer
) ELSE IF "%1" == "phpcbf" (
   @echo "phpcbf"
   composer phpcbf
) ELSE IF "%1" == "phpmd" (
   @echo "phpmd"
   composer phpmd > audit/1-phpmd.log   
   cat audit/1-phpmd.log
) ELSE IF "%1" == "phpcs" (
   @echo "phpcs"
   composer phpcs > audit/2-phpcs.log
   cat audit/2-phpcs.log
) ELSE IF "%1" == "phpstan" (
   @echo "phpstan"
   composer phpstan > audit/3-phpstan.log
   cat audit/3-phpstan.log
) ELSE IF "%1" == "phpcpd" (
   @echo "phpcpd"
   composer phpcpd > audit/5-phpcpd.log
   cat audit/5-phpcpd.log
) ELSE IF "%1" == "phpmnd" (
   @echo "phpmnd"
   composer phpmnd > audit/6-phpmnd.log
   cat audit/6-phpmnd.log
) ELSE (
   rm -rf audit
   audit fix
   audit next
)
