FROM php:8.1-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip librabbitmq-dev \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install amqp \
    && docker-php-ext-enable amqp

COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

