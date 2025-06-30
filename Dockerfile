# Utilise une image PHP officielle avec Node et Composer
FROM ghcr.io/render-examples/php:8.3

WORKDIR /var/www/html

# Copie le code source
COPY . .

# Installe les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Installe les dépendances JS et build les assets
RUN npm ci && npm run build

# Donne les bons droits (important pour Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose le port utilisé par Laravel
EXPOSE 10000

# Commande de démarrage
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
