#!/bin/bash
printenv | sed 's/^\(.*\)$/export "\1"/g' > "/.schedule-env.sh" | chmod +x "/.schedule-env.sh" &
if [ -d "/var/www/html/storage" ]; then chmod +x "/var/www/html/storage/*"; fi &
if [ -d "/var/www/html/storage/framework/cache" ]; then chmod +x "/var/www/html/storage/*"; fi &
if [ -d "/var/www/html/storage/framework/cache/data" ]; then chmod +x "/var/www/html/storage/*"; fi &
if [ ! -d "/var/log/supervisor" ]; then mkdir "/var/log/supervisor"; fi &
composer install --no-dev &
chown root /etc/crontabs/* &
supervisord &
crond &
docker-php-entrypoint php-fpm