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

# Installe les dépendances PHP et NPM, et build les assets
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build \
    && rm -rf node_modules

# Configuration des droits d'accès
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 755 public \
    && chmod -R 755 public/build \
    && mkdir -p storage/framework/{sessions,views,cache}

EXPOSE 8080

# Commande de démarrage
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
