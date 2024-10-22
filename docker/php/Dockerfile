FROM php:8.3.3RC1-apache

RUN apt-get update && apt-get install -y git

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www