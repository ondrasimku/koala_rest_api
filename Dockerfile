FROM php:8.3-apache

RUN a2enmod rewrite
RUN a2enmod ssl

RUN apt-get update \
  && apt-get install -y libzip-dev git wget libpq-dev zip unzip --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug && docker-php-ext-enable xdebug
ENV XDEBUG_MODE="debug,coverage"

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql

RUN rm /etc/apache2/sites-enabled/000-default.conf
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN ln -s /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/

COPY . /var/www
RUN chown www-data:www-data -R /var/www/

WORKDIR /var/www

COPY docker/entrypoint.sh docker/entrypoint.sh
RUN chmod +x docker/entrypoint.sh

CMD ["apache2-foreground"]
ENTRYPOINT ["docker/entrypoint.sh"]
