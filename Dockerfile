# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Copier tout le projet dans le dossier par défaut d'Apache
COPY . /var/www/html/

# Exposer le port 8080 (Render utilise généralement 8080)
EXPOSE 3000

# Lancer Apache en mode foreground
CMD ["apache2-foreground"]
