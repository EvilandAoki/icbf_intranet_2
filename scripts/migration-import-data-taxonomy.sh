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
run_migration "d7_taxonomy_term:tags"
run_migration "d7_taxonomy_term:forums"
run_migration "d7_taxonomy_term:event_calendar_status"
run_migration "d7_taxonomy_term:locations"
run_migration "d7_taxonomy_term:sige_process_type"
run_migration "d7_taxonomy_term:dependencies"
run_migration "d7_taxonomy_term:section_labels"
run_migration "d7_taxonomy_term:departments_municipalities"
run_migration "d7_taxonomy_term:secctions"
run_migration "d7_taxonomy_term:file_category"
run_migration "d7_taxonomy_term:tipos_de_ubicaci_n"
run_migration "d7_taxonomy_term:procedure"
run_migration "d7_taxonomy_term:hiring_process_type"
run_migration "d7_taxonomy_term:media_folders"
run_migration "d7_taxonomy_term:procedure_type"
run_migration "d7_taxonomy_term:cat_logo_servicios_dit"
run_migration "d7_taxonomy_term:periodo"
run_migration "d7_taxonomy_term:cargos_persona_"
run_migration "d7_taxonomy_term:relevancia"
run_migration "d7_taxonomy_term:eventos_icbf"
run_migration "d7_taxonomy_term:centro_de_ayuda"
run_migration "d7_taxonomy_term:catalogo_servicios_dit_old"

# Registra el final del proceso.
echo "=== Fin de migraciones: $(date) ===" >> "$LOGFILE"
