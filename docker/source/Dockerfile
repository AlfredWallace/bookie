FROM php:7.2-fpm-stretch

RUN apt-get update

RUN apt-get install --no-install-recommends -y unzip

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install opcache
RUN apt-get install --no-install-recommends -y libzip-dev \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install --no-install-recommends -y gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs
