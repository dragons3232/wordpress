FROM php:fpm-alpine
RUN sh /usr/local/bin/docker-php-ext-install mysqli
