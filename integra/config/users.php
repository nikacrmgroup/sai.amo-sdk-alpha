<?php
//тут можно задать значения наборы ключ значение для разного рода данных, например человеческое прочтение статусов
// транзакций

//https://passwordsgenerator.net/sha1-hash-generator/
return [
  [
    'login'    => 'admin',
    'name'     => 'Администратор',
    'password' => 'd033e22ae348aeb5660fc2140aec35850c4da997', //admin
    'roles'    => [
      'admin',

    ],

  ],
  [
    'login'    => 'production',
    'name'     => 'Менеджер Производство',
    'password' => '90a8834de76326869f3e703cd61513081ad73d3c', //production
    'roles'    => [
      'production_role',
    ],

  ],
  [
    'login'    => 'stock',
    'name'     => 'Менеджер Склад',
    'password' => 'ed487e1e87c675af89db011b2903f20f99b11c7d', //stock
    'roles'    => [
      'stock_role',
    ],

  ],
  [
    'login'    => 'spectator',
    'name'     => 'Менеджер Наблюдатель',
    'password' => 'ab0adbefe09bbb3c05316ebcc4d7f4cddba03ccc', //spectator
    'roles'    => [
      'spectator_role',
    ],

  ],
  [
    'login'    => 'user_4',
    'name'     => 'Менеджер Пшенкин',
    'password' => 'dddddddd',
    'roles'    => [

    ],

  ],


];