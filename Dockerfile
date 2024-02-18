FROM php:8-apache

RUN apt-get update && apt-get install -y default-mysql-client default-libmysqlclient-dev

# PHP extensions
RUN docker-php-ext-configure mysqli && docker-php-ext-install mysqli pdo_mysql
