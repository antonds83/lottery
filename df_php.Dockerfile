FROM php:7.4-fpm

WORKDIR /home/www/core/

COPY ./core .

RUN apt-get update \
    && apt-get install -y \
        libssh-dev \
        libzip-dev \
    	mariadb-client \
    && docker-php-ext-install \
        bcmath \
        sockets \
        zip \
    	mysqli \
    && docker-php-ext-enable mysqli
