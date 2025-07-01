# Image PHP officielle avec Composer et Node.js
FROM php:8.3-fpm

# Installe les dépendances système et Node.js
RUN apt-get update \
    && apt-get install -y git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie le code source
COPY . .

# Build les assets avant d'installer les dépendances PHP
RUN npm install \
    && npm run build \
    && rm -rf node_modules \
    && composer install --no-interaction --prefer-dist --optimize-autoloader

# Configuration des droits d'accès
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && find public -type f -exec chmod 644 {} \; \
    && find public -type d -exec chmod 755 {} \;

EXPOSE 8080

# Commande de démarrage
CMD mkdir -p storage/framework/{sessions,views,cache} \
    && touch database/database.sqlite \
    && php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
