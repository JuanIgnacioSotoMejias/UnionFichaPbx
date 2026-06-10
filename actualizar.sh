#!/bin/bash

# ==============================================================================
# Script de Actualización Segura para PBX Receptor y Ficha
# ==============================================================================

set -e # Detener ejecución si ocurre algún error

echo "=== 1. Iniciando descarga de cambios desde Git ==="
cd ~/UnionFichaPbx
git pull

echo "=== 2. Eliminando archivos antiguos de producción en Apache ==="
# Eliminamos de forma explícita el archivo 'hot' (que le dice a Laravel que use el
# servidor de desarrollo de Vite) y la carpeta antigua de builds.
sudo rm -f /opt/lampp/htdocs/pbx-receptor/public/hot
sudo rm -rf /opt/lampp/htdocs/pbx-receptor/public/build

echo "=== 3. Copiando el código actualizado a la ruta de Apache ==="
# Copiamos todo el contenido actualizado antes de compilar
sudo cp -r ~/UnionFichaPbx/pbx-receptor/* /opt/lampp/htdocs/pbx-receptor/

echo "=== 4. Compilando assets de frontend dentro del contenedor Docker ==="
# npm run build se ejecuta dentro de la carpeta /var/www/html del contenedor,
# la cual está mapeada a la ruta de producción. Esto generará la carpeta public/build
# con el manifest.json definitivo.
docker exec -i pbx_app_container npm run build

echo "=== 5. Limpiando la caché de la aplicación en el contenedor ==="
docker exec -i pbx_app_container php artisan config:clear
docker exec -i pbx_app_container php artisan route:clear

echo "=== ¡Actualización finalizada con éxito! ==="
echo "Nota: Recuerda recargar la página en tu navegador con Ctrl + F5 para vaciar la caché local de estilos."
