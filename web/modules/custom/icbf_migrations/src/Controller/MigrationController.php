<?php

namespace Drupal\icbf_migrations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;

/**
 * Controlador para ejecutar la migración de archivos Drupal 7.
 */
class MigrationController extends ControllerBase {

  /**
   * Lanza la migración upgrade_d7_file.
   */
  public function runD7File() {
    // Instancia de la migración definida en el YAML.
    $migration = \Drupal::service('plugin.manager.migration')
      ->createInstance('d7_file');

    // Ejecutable de migración con logger.
    $executable = new MigrateExecutable(
      $migration,
      new MigrateMessage()
    );

    // Importa todos los archivos públicos de D7 a D10.
    $executable->import();

    return [
      '#type'   => 'markup',
      '#markup' => $this->t('Migración de archivos D7 iniciada. Revisa los registros de migración para detalles.'),
    ];
  }

}
