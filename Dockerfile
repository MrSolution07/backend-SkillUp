FROM php:7.4-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN mkdir -p /var/www/html

RUN a2enmod rewrite

RUN echo "DirectoryIndex index.php index.html" > /etc/apache2/mods-enabled/dir.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
