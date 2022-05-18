FROM php:8.1-fpm
MAINTAINER OTA

RUN apt-get update && \
    apt-get install -y --force-yes --no-install-recommends \
        vim \
        libmemcached-dev \
        libmcrypt-dev \
        libreadline-dev \
        libgmp-dev \
        libzip-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        openssh-server \
        libmagickwand-dev \
        git \
        cron \
        nano \
        libxml2-dev \
        mariadb-client \
        supervisor \
        npm

RUN npm install chokidar

# Install soap extention
RUN docker-php-ext-install soap

# Install for image manipulation
RUN docker-php-ext-install exif

# Install the PHP pcntl extention
RUN docker-php-ext-install pcntl

# Install the PHP intl extention
RUN docker-php-ext-install intl

# Install the PHP gmp extention
RUN docker-php-ext-install gmp

# Install the PHP zip extention
RUN docker-php-ext-install zip

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install the PHP pdo_pgsql extention
RUN docker-php-ext-install pdo_pgsql

# Install the PHP bcmath extension
RUN docker-php-ext-install bcmath

#redis
RUN pecl install -o -f redis \
        && pecl install swoole \
		&&  rm -rf /tmp/pear \
		&&  docker-php-ext-enable redis swoole

# add mysqli
RUN printf "\n" | docker-php-ext-install mysqli

# add composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
	&& php composer-setup.php \
	&& php -r "unlink('composer-setup.php');" \
	&& mv composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer
RUN /usr/bin/composer global require squizlabs/php_codesniffer
RUN /usr/bin/composer global require phpunit/phpunit
RUN /usr/bin/composer global require friendsofphp/php-cs-fixer

# change www-data's uid and gid for laravel folder permisstion
RUN apt-get install -y --force-yes --no-install-recommends  && \
    usermod -u 1000 www-data && \
    groupmod -g 1000 www-data

# install xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN echo 'zend_extension=xdebug' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.mode=develop,debug,coverage' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.discover_client_host=0' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.client_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'session.save_path = "/tmp"' >> /usr/local/etc/php/php.ini

# setting crontab
COPY crontab /var/spool/cron/crontabs/root
RUN chmod 0644 /var/spool/cron/crontabs/root
RUN crontab /var/spool/cron/crontabs/root

# make permisstion
RUN chown -R www-data:www-data /var/www

# copy supervisor config
COPY ./supervisord* /etc/supervisor/conf.d/

WORKDIR /var/www/html/app

# copy project
COPY . .

# Open Ports
EXPOSE 9000

COPY ./app-entrypoint.sh /
RUN chmod -R 0755 /app-entrypoint.sh
ENTRYPOINT /app-entrypoint.sh
