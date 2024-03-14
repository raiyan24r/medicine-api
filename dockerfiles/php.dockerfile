FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

COPY . .

RUN docker-php-ext-install pdo pdo_mysql

RUN addgroup -g 1000 medicine_api && adduser -G medicine_api -g medicine_api -s /bin/sh -D medicine_api

USER medicine_api
