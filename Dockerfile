FROM php:7.4-cli
RUN apt-get update \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets
COPY . /home/application
WORKDIR /home/application
CMD [ "php", "./console/run", "-f", "./config/config.defaul.yaml" ]