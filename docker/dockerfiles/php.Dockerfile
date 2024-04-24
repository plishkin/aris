FROM php:7.1-fpm

RUN apt-get update \
    && apt-get install -y wget git zip curl sendmail \
    && apt-get clean all

RUN apt-get install -y libonig-dev libpq-dev && docker-php-ext-install mbstring
RUN apt-get install -y libxml2-dev && docker-php-ext-install xml
RUN apt-get install -y libcurl4-openssl-dev && docker-php-ext-install curl
RUN apt-get install -y zlib1g-dev libzip-dev && docker-php-ext-install zip

RUN docker-php-ext-install intl

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install gd

RUN docker-php-ext-install mysqli pdo_mysql

RUN ["chmod", "+x", "/usr/local/bin/docker-php-entrypoint"]

RUN echo "sendmail_path=/usr/sbin/sendmail -t -i" >> /usr/local/etc/php/conf.d/sendmail.ini
RUN sed -i '/#!\/bin\/sh/aservice sendmail restart' /usr/local/bin/docker-php-entrypoint
RUN sed -i '/#!\/bin\/sh/aecho "$(hostname -i)\t$(hostname) $(hostname).localhost" >> /etc/hosts' /usr/local/bin/docker-php-entrypoint

# Xdebug
ARG INSTALL_XDEBUG=false
COPY docker/configs/xdebug.ini /tmp/xdebug.ini
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug && mv /tmp/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini; \
    fi;

COPY --from=composer:2.2.0 /usr/bin/composer /usr/local/bin/composer

# And clean up the image
RUN rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

