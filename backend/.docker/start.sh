#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${CONTAINER_ENV:-production}

if [ "$env" != "local" ]; then
    # echo "Caching configuration..."
    # (cd /srv/app && php artisan config:cache && php artisan route:cache && php artisan view:cache)
    # (cd /var/www/html && .openshift/deploy.sh)
fi

# php /var/www/html storage:link --relative --force

if [ "$role" = "app" ]; then

    echo "role: app"
    php-fpm

elif [ "$role" = "queue" ]; then

    echo "role: queue"
    echo "SESSION_DRIVER: $SESSION_DRIVER"
    echo "CACHE_DRIVER: $CACHE_DRIVER"
    echo "QUEUE_CONNECTION: $QUEUE_CONNECTION..."
    php /var/www/html queue:work --verbose --tries=3 --timeout=90

elif [ "$role" = "scheduler" ]; then

    while [ true ]
    do
      php /var/www/html schedule:run --verbose --no-interaction &
      sleep 60
    done

elif [ "$role" = "artisan"]; then
    echo "role: artisan"

else
    echo "Could not match the container role \"$role\""
    exit 1
fi