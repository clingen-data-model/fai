FROM registry.hub.docker.com/library/node:17.9-alpine as builder

# Set the working directory
WORKDIR /usr/src

# Copy package.json & lock file
COPY ./package*.json .

# Install dependencies
RUN npm install

# Copy source to working directory (/usr/src/app)
COPY . ./

# build the app
ENV BUILD_ENV=docker
ENV NODE_OPTIONS=--openssl-legacy-provider
RUN npm run build

FROM registry.hub.docker.com/nginxinc/nginx-unprivileged:1.23

COPY ./.docker/nginx.conf /etc/nginx/nginx.conf
COPY ./.docker/http.conf.template /etc/nginx/templates/http.conf.template
COPY --from=builder /usr/src /usr/share/nginx/html

EXPOSE 8080/tcp