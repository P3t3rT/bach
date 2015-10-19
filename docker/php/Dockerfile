FROM php:5.6-apache
MAINTAINER Peter Termaten <peter.termaten@gmail.com>

# Apache
COPY bach3.conf /etc/apache2/sites-enabled/bach.conf

RUN a2enmod rewrite

RUN rm -rf /var/www/html && mkdir /var/www/html
RUN chown www-data:www-data /var/www/html

# PHP ini stuff
RUN echo "date.timezone=Europe/Amsterdam" >> /usr/local/etc/php/php.ini && \
    echo "short_open_tag=off" >> /usr/local/etc/php/php.ini

RUN apt-get update && apt-get install -y \
        libmcrypt-dev \
        libicu-dev \
        libmemcached-dev zlib1g-dev \
        php5-xdebug \
        git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install mcrypt pdo pdo_mysql intl mbstring \
    && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Adminer
RUN mkdir -p /var/www/adminer
RUN curl https://raw.githubusercontent.com/vrana/adminer/master/designs/pilot/adminer.css -o /var/www/adminer/adminer.css
RUN curl http://www.adminer.org/static/download/4.2.2/adminer-4.2.2-mysql-en.php -o /var/www/adminer/index.php
RUN chown -R www-data:www-data /var/www/adminer
#COPY adminer.conf /etc/apache2/sites-enabled/adminer.conf
COPY adminer.conf /etc/apache2/sites-enabled/000-default.conf

# Install Xdebug
RUN echo "zend_extension=/usr/lib/php5/20131226/xdebug.so" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.max_nesting_level=250" >> /usr/local/etc/php/conf.d/ext-xdebug.ini && \
    echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/ext-xdebug.ini

# Install caching stuff
RUN pecl install apcu-beta memcached && \
    echo "extension=memcached.so" >> "/usr/local/etc/php/conf.d/ext-memcached.ini" &&  \
    echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini && \
    echo "zend_extension=opcache.so" >> "/usr/local/etc/php/conf.d/ext-opcache.ini" && \
    echo "opcache.enable_cli=1" >> "/usr/local/etc/php/conf.d/ext-opcache.ini" && \
    echo "opcache.memory_consumption=128" >> "/usr/local/etc/php/conf.d/ext-opcache.ini" && \
    echo "opcache.interned_strings_buffer=8" >> "/usr/local/etc/php/conf.d/ext-opcache.ini" && \
    echo "opcache.max_accelerated_files=4000" >> "/usr/local/etc/php/conf.d/ext-opcache.ini" && \
    echo "opcache.fast_shutdown=1" >> "/usr/local/etc/php/conf.d/ext-opcache.ini" && \
    echo "opcache.save_comments=1" >> "/usr/local/etc/php/conf.d/ext-opcache.ini"

EXPOSE 80 9000

CMD ["apache2-foreground"]
