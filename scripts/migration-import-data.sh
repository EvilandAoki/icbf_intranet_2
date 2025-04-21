#!/bin/bash
set -x

# Variables de color
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # Sin color

# Archivo de log para registrar la salida de las migraciones.
LOGFILE="migraciones.log"

# Registra el inicio del proceso con la fecha.
echo "=== Inicio de migraciones: $(date) ===" >> "$LOGFILE"

# Función para ejecutar una migración, capturar la salida y registrar si se completó o no.
run_migration() {
  MIGRATION="$1"
  echo "=== Ejecutando migración: $MIGRATION ===" | tee -a "$LOGFILE"
  
  # Captura la salida y el código de salida del comando.
  output=$(vendor/bin/drush migrate:import "$MIGRATION" 2>&1)
  exit_status=$?
  
  # Registra la salida del comando.
  echo "$output" | tee -a "$LOGFILE"
  
  # Evalúa el código de salida y muestra el resultado, coloreando el mensaje de error en rojo.
  if [ $exit_status -eq 0 ]; then
    echo -e "${GREEN}Migración '$MIGRATION' completada exitosamente.${NC}" | tee -a "$LOGFILE"
  else
    echo -e "${RED}Migración '$MIGRATION' fallida.${NC}" | tee -a "$LOGFILE"
  fi
}

# Lista de migraciones a ejecutar
run_migration "d7_webform_submission"
run_migration "d7_menu_links"
run_migration "d7_forum_settings"
run_migration "d7_block"

# Registra el final del proceso.
echo "=== Fin de migraciones: $(date) ===" >> "$LOGFILE"
