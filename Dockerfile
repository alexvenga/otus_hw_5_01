FROM ubuntu:18.04

RUN apt-get update && \
    apt-get install -y nginx php-cli htop sudo

ENTRYPOINT ["nginx","-g","daemon off;"]

EXPOSE 80