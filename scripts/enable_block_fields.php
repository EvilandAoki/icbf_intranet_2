<?php

declare(strict_types=1);

use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;

$entity_type = 'block_content';
$bundle = 'footer';

// Servicios
$field_config_storage   = \Drupal::entityTypeManager()->getStorage('field_config');
$form_display_storage   = \Drupal::entityTypeManager()->getStorage('entity_form_display');
$view_display_storage   = \Drupal::entityTypeManager()->getStorage('entity_view_display');
$field_type_manager     = \Drupal::service('plugin.manager.field.field_type');
$logger                 = \Drupal::logger('enable_footer_fields');

// Cargar campos del bloque "footer"
$fields = $field_config_storage->loadByProperties([
  'entity_type' => $entity_type,
  'bundle' => $bundle,
]);

// Cargar o crear Form Display
$form_display = $form_display_storage->load("{$entity_type}.{$bundle}.default");
if (!$form_display) {
  $form_display = EntityFormDisplay::create([
    'targetEntityType' => $entity_type,
    'bundle' => $bundle,
    'mode' => 'default',
    'status' => TRUE,
  ]);
  $form_display->save();
}

// Cargar o crear View Display
$view_display = $view_display_storage->load("{$entity_type}.{$bundle}.default");
if (!$view_display) {
  $view_display = EntityViewDisplay::create([
    'targetEntityType' => $entity_type,
    'bundle' => $bundle,
    'mode' => 'default',
    'status' => TRUE,
  ]);
  $view_display->save();
}

foreach ($fields as $field) {
  $field_name = $field->getName();

  // Habilitar campo si está desactivado
  if (!$field->status()) {
    $field->set('status', TRUE);
    $field->save();
    $logger->notice("Campo {field} habilitado en {bundle}.", [
      'field' => $field_name,
      'bundle' => $bundle,
    ]);
  }

  // FORM DISPLAY: mostrar si está oculto
  $form_components = $form_display->getComponents();
  if (empty($form_components[$field_name]) || ($form_components[$field_name]['region'] ?? '') === 'hidden') {
    $field_def     = $field_type_manager->getDefinition($field->getType());
    $default_widget = $field_def['default_widget'] ?? 'string_textfield';

    $form_display->setComponent($field_name, ['type' => $default_widget]);
    $logger->notice("Campo {field} mostrado en formulario de bloque {bundle}.", [
      'field' => $field_name,
      'bundle' => $bundle,
    ]);
  }

  // VIEW DISPLAY: mostrar si está oculto
  $view_components = $view_display->getComponents();
  if (empty($view_components[$field_name]) || ($view_components[$field_name]['region'] ?? '') === 'hidden') {
    $field_def = $field_type_manager->getDefinition($field->getType());
    $default_formatter = $field_def['default_formatter'] ?? 'string';

    $view_display->setComponent($field_name, ['type' => $default_formatter]);
    $logger->notice("Campo {field} mostrado en vista del bloque {bundle}.", [
      'field' => $field_name,
      'bundle' => $bundle,
    ]);
  }
}

// Guardar cambios
$form_display->save();
$view_display->save();
