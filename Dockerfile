FROM php:8.1.0-apache
WORKDIR /var/www/html

# Mod Rewrite
RUN a2enmod rewrite

# Linux Library
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libxslt-dev \
    libzip-dev 

RUN curl -s https://deb.nodesource.com/setup_22.x | bash

RUN apt-get install nodejs -y

RUN apt-get install git -y
RUN apt-get install nano -y

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP Extension
RUN docker-php-ext-install gettext intl pdo_mysql gd soap xsl zip


RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
# cd storage
# mkdir logs
# mkdir framework
# mkdir framework/cache && framework/cache/data
# mkdir framework/sessions
# mkdir framework/testing
# mkdir framework/views
# RUN chown -R www-data:www-data /var/www/html/contento.web/storage /var/www/html/contento.web/bootstrap/cache
# RUN chmod -R 775 /var/www/html/contento.web/storage /var/www/html/contento.web/bootstrap/cache
COPY virtualhost.conf /etc/apache2/sites-enabled/000-default.conf