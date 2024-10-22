FROM php:8.3.3RC1-apache

RUN apt-get update && apt-get install -y git
#In this place you can specify all the extensions you need.
# RUN docker-php-ext-install pdo_mysql

RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# RUN apt install -y libmemcached-dev zlib1g-dev libssl-dev
# RUN yes '' | pecl install -f memcached-3.2.0 \
#   && docker-php-ext-enable memcached \
#   && docker-php-ext-install sockets

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www