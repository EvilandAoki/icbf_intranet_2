#!/bin/sh

echo "🧹 Eliminando configuraciones de migración de Drupal 7..."

for ID in \
  upgrade_d7_bean_block \
  upgrade_d7_bean_block_type \
  upgrade_d7_comment_type \
  upgrade_d7_field \
  upgrade_d7_field_formatter_settings \
  upgrade_d7_field_instance \
  upgrade_d7_field_instance_widget_settings \
  upgrade_d7_filter_format \
  upgrade_d7_taxonomy_vocabulary \
  upgrade_d7_view_modes \
  upgrade_fontawesome_settings \
  upgrade_taxonomy_manager_settings \
  upgrade_youtube_settings \
  upgrade_d7_file
do
  echo "⏳ Eliminando: migrate_plus.migration.$ID"
  vendor/bin/drush config:delete "migrate_plus.migration.$ID" -y
done

echo "✅ ¡Listo! Todas las configuraciones fueron eliminadas."
