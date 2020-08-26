FROM php:7.4-fpm-alpine as BASE

RUN docker-php-ext-install sockets

COPY . /

ENV HOST='127.0.0.1'
ENV PORT='514'

WORKDIR /
RUN chmod a+r feed.php

CMD [ "php", "./feed.php" ]

# FROM php:7.4-cli-alpine as DAEMON