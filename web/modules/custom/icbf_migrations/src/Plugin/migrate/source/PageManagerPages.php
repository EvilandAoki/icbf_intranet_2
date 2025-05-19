<?php

namespace Drupal\icbf_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Proporciona una fuente para las páginas de Page Manager.
 *
 * @MigrateSource(
 *   id = "d7_page_manager_pages"
 * )
 */
class PageManagerPages extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('page_manager_pages', 'p')
        ->fields('p', ['pid', 'name', 'admin_title', 'path', 'task', 'admin_description', 'access', 'menu', 'arguments', 'conf']);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'pid' => $this->t('ID de la página'),
      'name' => $this->t('Nombre del sistema'),
      'admin_title' => $this->t('Etiqueta'),
      'admin_description' => $this->t('Descripción'),
      'path' => $this->t('Ruta'),
    ];
  }
  

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'pid' => [
        'type' => 'integer',
        'alias' => 'p',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // \Drupal::logger('migrate_debug')->debug('<pre>' . print_r($row->getSource(), TRUE) . '</pre>');

  $name = $row->getSourceProperty('name');
  if (empty($name)) {
    \Drupal::logger('migrate_debug')->error('Fila sin nombre detectada, omitiendo...');
    return FALSE;
  }

  $task = $row->getSourceProperty('task');
  $subtask = $row->getSourceProperty('name');


  $handlers = $this->select('page_manager_handlers', 'h')
    ->fields('h', ['did', 'name', 'task', 'subtask', 'handler', 'weight', 'conf'])
    ->condition('task', $task)
    ->condition('subtask', $subtask)
    ->execute()
    ->fetchAll(\PDO::FETCH_ASSOC);

  $formatted_handlers = [];
  foreach ($handlers as $handler) {
    $conf = @unserialize($handler['conf']);
    if ($conf === false && $handler['conf'] !== 'b:0;') {
      $conf = [];
    }
  
    $formatted_handlers[] = [
      'id' => $handler['name'] . '_variant',
      'weight' => $handler['weight'],
      'plugin_id' => $handler['handler'],
      'configuration' => $conf,
    ];
  }

  \Drupal::logger('Formatted Handlers')->debug('<pre>Formatted Handlers: @data</pre>', ['@data' => print_r($formatted_handlers, TRUE)]);


  $path = trim($row->getSourceProperty('path'));
  if (!empty($path) && !str_starts_with($path, '/') && !str_starts_with($path, '?') && !str_starts_with($path, '#')) {
    $row->setSourceProperty('path', '/' . $path);
  }

  $row->setSourceProperty('handlers', $formatted_handlers);
  $row->setSourceProperty('id', $name);

  return parent::prepareRow($row);
  }
  
}
