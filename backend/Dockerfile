# Final stage
FROM jward3/php:8.1-fpm

# Set a bunch of labels for k8s and Openshift.
LABEL maintainer="TJ Ward" \
    io.openshift.tags="laravel-epam:v1" \
    io.k8s.description="A system to manage the Expert Panel Application process." \
    io.openshift.expose-services="8080:http,8443:https" \
    io.k8s.display-name="epam version 1" \
    io.openshift.tags="php,apache"

# Use root user to set things up.
USER root

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# PHP configs (including loading phprdkafka)
COPY ./.docker/php/conf.d/* $PHP_INI_DIR/conf.d/

COPY ./composer.lock ./composer.json /var/www/html/
COPY ./database/seeders ./database/seeders
COPY ./database/factories ./database/factories

RUN composer install \
        # --optimize-autoloader \
        --no-interaction \
        --no-plugins \
        --no-scripts \
        --no-dev \
        --prefer-dist

# We need this directory because Tinker depends on psysh
# and psysh doesn't provide a good way to change the directory
# to the project directory.
RUN mkdir -p /.config/psysh \
    && chgrp -R 0 /.config/psysh \
    && chmod g+wx /.config/psysh

# Copy the source code.
COPY . /var/www/html

# Copy the start script
COPY .docker/start.sh /usr/local/bin/start

# Copy over the build artifacts from the node build container.
# COPY --from=builder /usr/src/dist ./public
# COPY --from=builder /usr/src/dist/index.html ./resources/views/app.blade.php

# Change ownership of files so non-root user can use them.
RUN chgrp -R 0 /var/www/html \
    && chmod -R g+w /var/www/html \
    && chmod g+x /var/www/html/deploy.sh \
    && chmod g+x /usr/local/bin/start
    # && pecl install xdebug-2.9.5 \
    # && docker-php-ext-enable xdebug \

# Link the uploads storage directory to public for downloads.
RUN php artisan storage:link

# Switch to non-root user for security (and to make OpenShift happy).
USER 1001

# Run the start command.
# CMD ["/usr/local/bin/start"]
