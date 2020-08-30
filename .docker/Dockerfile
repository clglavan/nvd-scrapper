FROM php:7.4-fpm-alpine as BASE

#FROM gcr.io/google.com/cloudsdktool/cloud-sdk:alpine

ENV BUCKET=0
ENV PROJECTID=0
ENV FILENAME="nvd-results.json"
ENV FORMAT="json"
ENV GOOGLE_APPLICATION_CREDENTIALS=/key.json

COPY . /
WORKDIR /

RUN chmod a+r feed.php

CMD [ "php", "./feed.php" ]