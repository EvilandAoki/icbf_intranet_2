#!/bin/bash

# Leer la lista de módulos desde el archivo
while IFS= read -r module; do
  echo "Instalando módulo: $module"
  drush en "$module" -y
done < ./modulos.txt

echo "Todos los módulos han sido instalados."
