FROM php:7.4-apache

# Copy les fichier utile
COPY . /var/www/html/

# Install les dépendances pour connecter l'API au la BDD mysql
RUN docker-php-ext-install pdo pdo_mysql
