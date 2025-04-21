<?php

declare(strict_types=1);

use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Form\FormDisplayInterface;

// Arrancamos Drupal si fuera necesario (Drush php:script ya lo hace).
// \Drupal::service('kernel')->boot();

// Cargamos todos los field configs de tipo “node”.
/** @var \Drupal\field\FieldConfigInterface[] $fields */
$fields = \Drupal::entityTypeManager()
  ->getStorage('field_config')
  ->loadByProperties(['entity_type' => 'node']);

// Servicios necesarios.
$form_display_storage = \Drupal::entityTypeManager()->getStorage('entity_form_display');
$field_type_manager   = \Drupal::service('plugin.manager.field.field_type');
$logger                = \Drupal::logger('enable_fields_script');

foreach ($fields as $field) {
  $bundle    = $field->getTargetBundle();
  $fieldName = $field->getName();

  // 1) Habilitar el field si está deshabilitado.
  if (!$field->status()) {
    $field->set('status', TRUE);
    $field->save();
    $logger->notice(
      'Habilitado campo {field} en bundle {bundle}.',
      ['field' => $fieldName, 'bundle' => $bundle]
    );
  }

  // 2) Sacar el field de “hidden” en el formulario.
  /** @var FormDisplayInterface|null $formDisplay */
  $formDisplay = $form_display_storage->load("node.{$bundle}.default");
  if ($formDisplay) {
    $components = $formDisplay->getComponents();
    $isHidden   = empty($components[$fieldName]) || ($components[$fieldName]['region'] ?? '') === 'hidden';

    if ($isHidden) {
      // Obtenemos el widget por defecto definido en el plugin del field type.
      $type_def     = $field_type_manager->getDefinition($field->getType());
      $defaultWidget = $type_def['default_widget'] ?? 'string_textfield';

      $formDisplay->setComponent($fieldName, [
        'type' => $defaultWidget,
        // Opcional: ajusta peso o settings si lo necesitas.
        // 'weight'   => 0,
        // 'settings' => [],
      ]);
      $formDisplay->save();

      $logger->notice(
        'Sacado de “hidden” el campo {field} en el formulario de bundle {bundle}.',
        ['field' => $fieldName, 'bundle' => $bundle]
      );
    }
  }
}
