FROM registry.hub.docker.com/library/nginx:1.21

COPY ./.docker/nginx.conf /etc/nginx/nginx.conf
COPY . /usr/share/nginx/html

USER 1001

EXPOSE 8080/tcp