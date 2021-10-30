### BASE STAGE ###
FROM php:8.0-fpm-alpine AS base

RUN apk add --no-cache git openssh
RUN docker-php-ext-install pdo

# install pcov
RUN git clone --depth 1 https://github.com/krakjoe/pcov.git /usr/src/php/ext/pcov \
    && docker-php-ext-configure pcov --enable-pcov \
    && docker-php-ext-install pcov


### BUILD STAGE ###
FROM base AS build

# install composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer


### DEVELOPMENT STAGE ###
FROM build AS dev

ENV PHP_IDE_CONFIG serverName=xdebug-package-payment

# install xdebug
RUN git clone -b master --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && echo -e "xdebug.mode=debug\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.client_port=9003\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.discover_client_host=1\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.start_with_request=default\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.idekey=PHPSTORM\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.cli_color=1\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.max_nesting_level=1000\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo -e "xdebug.output_dir=/tmp/debug\n" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && docker-php-ext-install xdebug \
    && mkdir /tmp/debug
