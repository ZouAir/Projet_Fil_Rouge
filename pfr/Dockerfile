FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony

# Cette ligne est nouvelle 👇
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get -y update
RUN apt-get -y install git
RUN git config --global user.email "votre@email.com" \
    && git config --global user.name "Votre Nom"