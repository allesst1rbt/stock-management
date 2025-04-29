FROM php:8.3-fpm
RUN curl -sL https://deb.nodesource.com/setup_18.x -o nodesource_setup.sh
RUN ["sh",  "./nodesource_setup.sh"]
RUN apt-get update && apt-get install -y git nodejs  openssl  unzip libmcrypt-dev  libzip-dev libxml2-dev libonig-dev  \
    libmagickwand-dev --no-install-recommends zip
RUN pecl install mcrypt-1.0.7
RUN docker-php-ext-enable mcrypt
        
RUN docker-php-ext-install gd 
RUN docker-php-ext-install xml
RUN docker-php-ext-install pdo
RUN docker-php-ext-install mbstring 
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip

COPY ./package.json /var/www/html/package.json

COPY ./docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer