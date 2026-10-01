# NCS Intranet - PHP 8.3 + Apache application image.
# PHP 8.3 is used because the IMAP extension (ticket e-mail piping) was removed from core in 8.4.
FROM php:8.3-apache-bookworm

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions pgsql pdo_pgsql intl gd zip imap bcmath exif opcache \
 && a2enmod rewrite headers remoteip expires \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/php.ini "$PHP_INI_DIR/conf.d/zz-ncs.ini"
COPY docker/apache/ncs.conf /etc/apache2/sites-available/000-default.conf
COPY docker/apache/security.conf /etc/apache2/conf-available/security.conf

WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html/

COPY --chmod=0755 docker/php/entrypoint.sh /usr/local/bin/ncs-entrypoint
ENTRYPOINT ["ncs-entrypoint"]
CMD ["apache2-foreground"]

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD curl -fsS -o /dev/null -H "X-Forwarded-Proto: https" http://localhost/index.php/signin || exit 1
