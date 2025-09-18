FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copier ton app
COPY . /var/www/html/

# Donner les bons droits à Apache
RUN chown -R www-data:www-data /var/www/html

# Exposer Apache
EXPOSE 80

CMD ["apache2-foreground"]
