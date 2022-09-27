FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --update --no-cache libgd libpng-dev libjpeg-turbo-dev freetype-dev

ADD php.ini /usr/local/etc/php/conf.d/php.ini

EXPOSE 9000

# Start php server
CMD ["php-fpm"]