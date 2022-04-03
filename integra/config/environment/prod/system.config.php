<?php

return [

  'cache'         => [
    'leads_in_pipelines' => [
      'ttl'  => 1,
      'desc' => 'Кеш лидов из выбранных воронок',
    ],
    'account'            => [
      
      'ttl'  => 30 * 24 * 60 * 60,
      'desc' => 'Кеш аккаунта',
    ],

    'products_catalog_id' => [
      'ttl'  => 60 * 24 * 3600,
      'desc' => 'ID каталога товаров',
    ],
    'all_pipelines'       => [
      'ttl'  => 10 * 24 * 3600,
      'desc' => 'Все воронки',
    ],
    'all_active_statuses' => [
      'ttl'  => 10 * 24 * 3600,
      'desc' => 'Все активные статусы воронок',
    ],
    'all_products'        => [
      'ttl'  => 5 * 3600,
      'desc' => 'Все товары',
    ],
    'all_contacts'        => [
      'ttl'  => 15 * 60,
      'desc' => 'Все Контакты',
    ],

  ],
  'max_log_files' => 6, //кол-во файлов логирования для ротации
  'php'           => [
    'max_execution_time'      => 1000,
    # Session timeout, 2628000 sec = 1 month, 604800 = 1 week, 57600 = 16 hours, 86400 = 1 day
    'session.gc_maxlifetime'  => 2628000,
    'session.cookie_lifetime' => 2628000,


  ],

  'refresh_timeout' => 5,
  'logger_channels' => [
    'request_logger'   => 'request-logger',
    'app_logger'       => 'app-logger',
    'logger'           => 'all-logger',
    'profiling_logger' => 'profiling',
    'auth_logger'      => 'auth',

  ],

];