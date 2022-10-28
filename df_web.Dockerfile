FROM nginx:latest

WORKDIR /home/www/core/

COPY ./core .

COPY ./site.conf /etc/nginx/conf.d/default.conf
