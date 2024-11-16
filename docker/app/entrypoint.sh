#!/bin/bash
# Start php-fpm in the background
php-fpm -D

# Запуск cron в фоновом режиме
crond -f -L /var/log/cron.log &

# Start supervisord to manage Horizon and other processes
exec supervisord -c /etc/supervisord.conf
