<?php

declare(strict_types=1);

use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;

// Cargamos todos los field configs de tipo “node”.
/** @var \Drupal\field\FieldConfigInterface[] $fields */
$fields = \Drupal::entityTypeManager()
  ->getStorage('field_config')
  ->loadByProperties(['entity_type' => 'node']);

// Servicios necesarios.
$view_display_storage = \Drupal::entityTypeManager()->getStorage('entity_view_display');
$field_type_manager   = \Drupal::service('plugin.manager.field.field_type');
$logger                = \Drupal::logger('enable_fields_display');

foreach ($fields as $field) {
  if (!$field->status()) {
    // Ignora campos desactivados.
    continue;
  }

  $bundle    = $field->getTargetBundle();
  $fieldName = $field->getName();

  /** @var EntityViewDisplayInterface|null $viewDisplay */
  $viewDisplay = $view_display_storage->load("node.{$bundle}.default");
  if ($viewDisplay) {
    $components = $viewDisplay->getComponents();
    $isHidden   = empty($components[$fieldName]) || ($components[$fieldName]['region'] ?? '') === 'hidden';

    if ($isHidden) {
      // Obtenemos el formatter por defecto para este tipo de campo.
      $type_def          = $field_type_manager->getDefinition($field->getType());
      $defaultFormatter  = $type_def['default_formatter'] ?? 'string';

      $viewDisplay->setComponent($fieldName, [
        'type' => $defaultFormatter,
        // 'label' => 'above',        // Opcional
        // 'weight' => 0,             // Opcional
        // 'settings' => [],          // Opcional
      ]);
      $viewDisplay->save();

      $logger->notice(
        'Campo {field} visible en presentación pública de bundle {bundle}.',
        ['field' => $fieldName, 'bundle' => $bundle]
      );
    }
  }
}
