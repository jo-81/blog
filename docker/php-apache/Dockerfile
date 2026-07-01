FROM php:8.1-apache

# Installation des dépendances système de base
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configuration et installation des extensions PHP (GD pour Intervention Image, PDO pour MySQL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql intl zip

# Installation et activation de Xdebug pour Pest
RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

# Activation du module de réécriture d'URL d'Apache (Crucial pour le routage)
RUN a2enmod rewrite

# Modification du DocumentRoot d'Apache pour pointer directement sur le dossier public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -rif -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf.d/*.conf 2>/dev/null || true

# Augmentation de la taille limite d'upload de fichiers
RUN echo "upload_max_filesize = 20M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/uploads.ini

# Installation globale de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html