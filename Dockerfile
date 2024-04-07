FROM php:8.3-fpm AS phonebook_test_php

RUN echo "start"

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    libonig-dev \
    libfreetype6-dev \
    libjpeg-dev  \
    libmcrypt-dev \
    libzip-dev \
    zip

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

RUN docker-php-ext-install -j$(nproc) mbstring pdo pdo_mysql exif bcmath gd zip opcache && docker-php-ext-enable opcache

WORKDIR /var/www/phonebook_test

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

FROM nginx AS phonebook_test_nginx

ADD docker/nginx/phonebook_test.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www/phonebook_test
