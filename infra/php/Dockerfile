FROM php:8.2-fpm-buster

RUN pecl install xdebug-3.2.1

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt -y install symfony-cli zip unzip

WORKDIR /app
