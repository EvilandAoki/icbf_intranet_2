<?php

namespace Drupal\icbf_migrations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\Plugin\MigrationInterface;

class MigrationController extends ControllerBase {

  public function runD7File() {
    $migration = \Drupal::service('plugin.manager.migration')
      ->createInstance('d7_file');

    $executable = new MigrateExecutable(
      $migration,
      new MigrateMessage()
    );

    $result = $executable->import();

    switch ($result) {
      case MigrationInterface::RESULT_COMPLETED:
        $message = $this->t('Migración completada exitosamente.');
        break;

      case MigrationInterface::RESULT_INCOMPLETE:
        $message = $this->t('Migración incompleta. Algunos elementos no se migraron.');
        break;

      case MigrationInterface::RESULT_FAILED:
        $message = $this->t('La migración falló. Revisa los registros para más detalles.');
        break;

      default:
        $message = $this->t('Resultado desconocido de la migración.');
        break;
    }

    return [
      '#type' => 'markup',
      '#markup' => $message,
    ];
  }

}
