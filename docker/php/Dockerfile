FROM php:8.1-fpm-alpine

RUN apk update --update-cache && \
    apk upgrade --available && \
    apk --no-cache add \
    openssh \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

RUN apk add git

RUN apk add icu-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-configure intl && docker-php-ext-install intl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    gd \
    zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && composer --version

EXPOSE 9000

WORKDIR /app
