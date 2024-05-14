FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
libzip-dev \
zip \
unzip \
libonig-dev \
libcurl4-openssl-dev \
pkg-config

RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install pdo_mysql mysqli
COPY . ./app
WORKDIR /var/www/html
EXPOSE 9000
