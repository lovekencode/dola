# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Modifier la config Apache pour écouter sur le port 3000
RUN sed -i 's/80/3000/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Copier tout le projet dans le dossier par défaut d'Apache
COPY . /var/www/html/

# Exposer le port attendu par Coolify
EXPOSE 3000

# Lancer Apache en mode foreground
CMD ["apache2-foreground"]
