FROM php:8.0-apache

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY php/config/apache-config.conf /etc/apache2/sites-available/000-default.conf

COPY php/public/ /var/www/public

RUN  chmod 777 /var/www/public

WORKDIR /var/www/public

EXPOSE 80
