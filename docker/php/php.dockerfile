FROM php:8.2-fpm-alpine

# Смена зеркала на CDN
RUN sed -i 's|http://dl.alpinelinux.org/alpine|https://dl-cdn.alpinelinux.org/alpine|g' /etc/apk/repositories \
    && apk update


ARG UID
ARG GID

ENV UID=${UID:-1000}
ENV GID=${GID:-1000}

WORKDIR /var/www/html

RUN addgroup -g ${GID} laravel \
    && adduser -G laravel -D -s /bin/sh -u ${UID} laravel

RUN sed -i "s/^user = .*/user = laravel/" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s/^group = .*/group = laravel/" /usr/local/etc/php-fpm.d/www.conf

# Установим необходимые расширения PHP
RUN apk add --no-cache \
    git \
    bash \
    zip \
    unzip \
    libzip-dev \
    autoconf \
    gcc \
    g++ \
    make \
    && docker-php-ext-install zip pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf gcc g++ make

EXPOSE 9000
CMD ["php-fpm"]

