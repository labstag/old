FROM php:7.4-fpm
RUN apt-get update
RUN apt-get install -y git zip libzip-dev 
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev
RUN apt-get install -y zlib1g-dev libicu-dev g++
RUN apt-get install -y libldap2-dev
RUN apt-get install -y libxml++2.6-dev
#  INSTALL FOR GD
RUN apt-get install -y 
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
# INSTALL FOR INTL
RUN docker-php-ext-install intl
RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/
RUN docker-php-ext-install ldap
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install shmop
# INSTALL FOR SOAP
RUN docker-php-ext-install soap
RUN docker-php-ext-install sockets
RUN docker-php-ext-install xmlrpc
# INSTALL FOR OTHER
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -Sl https://deb.nodesource.com/setup_13.x | bash
RUN apt-get install -y nodejs
WORKDIR /usr/local/apache2/htdocs
