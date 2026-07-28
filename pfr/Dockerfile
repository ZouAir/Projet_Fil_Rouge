FROM php:8.2-apache

# Extensions MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Enable mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html