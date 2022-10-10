FROM php:7.3-fpm-alpine

WORKDIR /var/www

RUN apk update && apk add \
    build-base

RUN docker-php-ext-install pdo_mysql

RUN addgroup -g 10000 -S www && \
    adduser -u 10000 -S www -G www

USER www

COPY --chown=www:www . /var/www

EXPOSE 9000

