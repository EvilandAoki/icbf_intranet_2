<?php

namespace Drupal\icbf_migrations\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Plugin de proceso personalizado para Page Manager.
 *
 * @MigrateProcessPlugin(
 *   id = "page_manager_process"
 * )
 */
class PageManagerProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Realiza transformaciones personalizadas aquí.
    return $value;
  }

}
