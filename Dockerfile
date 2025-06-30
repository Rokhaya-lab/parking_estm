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

# Installe les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Installe les dépendances JS et build les assets
RUN npm ci && npm run build

# Donne les bons droits (important pour Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000

# Commande de démarrage
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
