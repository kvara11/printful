FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

RUN composer install

EXPOSE 80