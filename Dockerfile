# Utiliser une image PHP plus stable
FROM php:8.2-apache

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Installation de Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copie des fichiers du projet
COPY . .

# Installation des dépendances et build
RUN composer install --no-interaction --no-dev --optimize-autoloader \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

# Configuration des droits
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache public

# Configuration d'Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Variable d'environnement pour le port
ENV PORT=8080

# Commande de démarrage
CMD sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf && \
    sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/*.conf && \
    mkdir -p storage/framework/{sessions,views,cache} && \
    touch database/database.sqlite && \
    php artisan optimize:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    apache2-foreground
