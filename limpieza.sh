#!/bin/bash

echo "Limpiando caché de Laravel..."
php artisan view:clear
php artisan cache:clear
php artisan config:clear

echo "Verificando migraciones pendientes..."
if php artisan migrate --pretend | grep -q "Nothing to migrate"; then
    echo "No hay migraciones pendientes."
else
    echo "Ejecutando migraciones..."
    php artisan migrate --force
fi

echo "Proceso completado. ✅"
