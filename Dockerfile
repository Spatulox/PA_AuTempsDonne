FROM php:7.4-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Copier le code source de votre application PHP
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

# Définir les permissions et le propriétaire/groupe des fichiers
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

# Exposer le port 80 pour le serveur web Apache
EXPOSE 80

# Démarrer le serveur Apache
CMD ["apache2-foreground"]
