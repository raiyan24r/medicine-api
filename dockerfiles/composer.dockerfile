FROM composer:2

RUN addgroup -g 1000 medicine_api && adduser -G medicine_api -g medicine_api -s /bin/sh -D medicine_api

USER medicine_api

WORKDIR /var/www/html

ENTRYPOINT ["composer"]
