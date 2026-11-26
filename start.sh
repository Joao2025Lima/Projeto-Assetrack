#!/usr/bin/env bash
php artisan storage:link --force
php artisan migrate --force
apache2-foreground
