@echo off
IF "%1" == "" (
   @echo "Indiquer le fichier ou le répertoire à traiter"
) ELSE (
    echo "PHPCSFIXER"
	composer php-cs-fixer
   .\bin\php-cs-fixer fix --config=php_cs.dist %1
   echo "PHPCBF"
   .\bin\phpcbf -d memory_limit=-1 %1 --report=diff -p --extensions=php --ignore=*/src/Migrations/*,src/Kernel.php
   echo "PHPMD"
   .\bin\phpmd %1 text phpmd.xml
   echo "PHPCS"
   .\bin\phpcs %1 --report=full --report-width=120 --extensions=php --ignore=*/src/Migrations/*,src/Kernel.php
   echo "PHPSTAN"
   .\bin\phpstan analyse %1
)