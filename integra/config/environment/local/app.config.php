<?php

/*Настройки приложения*/

return [

    'app_name'        => 'SAI Партнеры 🤖',
    'debug'           => true,  //режим дебага, системная опция
    'profiling'       => false,  //режим профилирования, системная опция
    'dummy_request'   => false,  //режим запроса из заглушки
    'dummy_file_name' => 'form.json',
    'script_folder'   => 'integra',

    'request_resource' => 'shop.partners', //
    'database'         => [

      'name'     => 'sai_shop_partners',
      'username' => 'root',
      'password' => '',

    ],


    'refresh_timeout' => 5,


];