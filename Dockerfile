FROM php:8-fpm-alpine

WORKDIR /app

RUN apk update \
    && apk add autoconf g++ make \
    && apk add bash \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del --purge autoconf g++ make \
    && docker-php-ext-install pdo pdo_mysql