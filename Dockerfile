# Utiliser une image PHP plus stable
FROM php:8.2-apache

# Activer les modules Apache nécessaires
RUN a2enmod rewrite headers

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

# Création des dossiers nécessaires
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache \
    && mkdir -p public/build

# Installation des dépendances et build
RUN composer install --no-interaction --no-dev --optimize-autoloader \
    && npm ci \
    && npm run build \
    && rm -rf node_modules

# Configuration des droits (après création des dossiers)
RUN chown -R www-data:www-data . \
    && chmod -R 755 . \
    && chmod -R 777 storage bootstrap/cache \
    && chmod -R 775 public/build \
    && chown -R www-data:www-data public/build

# Configuration d'Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Variable d'environnement pour le port
ENV PORT=8080
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Configuration du DocumentRoot d'Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Commande de démarrage
CMD mkdir -p storage/framework/{sessions,views,cache} ; \
    touch database/database.sqlite ; \
    chown -R www-data:www-data storage bootstrap/cache database ; \
    php artisan optimize:clear ; \
    php artisan config:cache ; \
    php artisan route:cache ; \
    php artisan view:cache ; \
    php artisan migrate --force ; \
    php artisan db:seed --force ; \
    apache2-foreground
