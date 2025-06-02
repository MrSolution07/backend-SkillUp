FROM php:7.4-apache

RUN mkdir -p /var/www/html

RUN a2enmod rewrite

# Ensure index.php is the default index
RUN echo "DirectoryIndex index.php index.html" > /etc/apache2/mods-enabled/dir.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html