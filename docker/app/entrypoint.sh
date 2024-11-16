#!/bin/bash
# Start php-fpm in the background
php-fpm -D

# Start supervisord to manage Horizon and other processes
exec supervisord -c /etc/supervisord.conf
