FROM php:8.2-fpm-buster

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt -y install symfony-cli zip unzip

WORKDIR /app
