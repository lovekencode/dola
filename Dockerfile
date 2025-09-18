FROM php:8.2-apache

# Installer extensions PHP
RUN docker-php-ext-install pdo pdo_mysql

# Ajouter Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier ton projet dans le container
WORKDIR /var/www/html
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
