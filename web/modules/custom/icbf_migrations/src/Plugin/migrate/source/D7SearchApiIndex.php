<?php

namespace Drupal\icbf_migrations\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * @MigrateSource(
 *   id = "d7_search_api_index"
 * )
 */
class D7SearchApiIndex extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('search_api_index', 'i')
      ->fields('i', [
        'machine_name',  // clave de la entidad config
        'name',          // etiqueta
        'description',   // descripción
        'server',        // relación: machine_name de search_api_server
        'item_type',     // tipo de item indexado
        'options',       // configuración serializada
        'enabled',       // habilitado
        'read_only',     // solo lectura
        'status',        // exportable
        'module',        // módulo provisto
      ]);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'machine_name' => $this->t('Index machine name'),
      'name'         => $this->t('Index label'),
      'description'  => $this->t('Description'),
      'server'       => $this->t('Server machine name'),
      'item_type'    => $this->t('Datasource plugin'),
      'options'      => $this->t('Serialized options'),
      'enabled'      => $this->t('Enabled flag'),
      'read_only'    => $this->t('Read only flag'),
      'status'       => $this->t('Exportable status'),
      'module'       => $this->t('Defining module'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'machine_name' => [
        'type' => 'string',
        'alias' => 'i',
      ],
    ];
  }
}
