FROM registry.hub.docker.com/nginxinc/nginx-unprivileged:1.23

COPY ./.docker/nginx.conf /etc/nginx/nginx.conf
COPY . /usr/share/nginx/html

EXPOSE 8080/tcp