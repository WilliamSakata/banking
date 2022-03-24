FROM php:8.1-fpm-alpine

WORKDIR /app

RUN apk update \
    && apk add autoconf g++ make \
    && apk add bash \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del --purge autoconf g++ make \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer global require "squizlabs/php_codesniffer=*" --dev