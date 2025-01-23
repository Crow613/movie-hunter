FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      docker-php-ext-install gd && \
      docker-php-ext-install zip && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

# Copy the HTML, CSS, and JavaScript files into the container
COPY public/js/jquery-func.js /usr/share/nginx/html/
COPY public/js/jquery-1.4.2.min.js /usr/share/nginx/html/
COPY public/css/style.css /usr/share/nginx/html/
COPY public/css/ie6.css /usr/share/nginx/html/

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.ddoc/php.ini

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www
