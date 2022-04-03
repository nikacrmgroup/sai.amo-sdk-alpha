<?php

use Nikacrm\Core\Amo\Factories\ICustomFieldsTypes;

//тут можно задать значения наборы ключ - значение для данных с форм и прочего api
/*Если не указан тип, то по умолчанию text*/
/*Список типов в ICustomFieldsTypes*/
/*Для системных можно тип опустить*/

return [
  'forms' => [
    'default' => [
      'pipeline_id'     => 1731979,
      'status_id'       => 26034988,
      'lead_name'       => 'Заказ из ЛК',
      'entities_create' => [
        'lead',
      ],
      'mapping'         => [
          /*Если есть такое поле как custom###, то пишем в амо поле значение, которое указано в конфиге*/
          //          'custom###form_name'         => [
          //            'id'        => 'custom###form_name',
          //            'mapped_id' => 'lead.cf.form_name',
          //            'value'     => 'Форма «Аккредитация»',
          //          ],
          //          'custom###lead_contact_name' => [
          //            'id'        => 'custom###lead_contact_name',
          //            'mapped_id' => 'lead.cf.name',
          //            'value'     => 'entity###contact_name',
          //          ],


          'lastname'          => [
            'id'        => 'lastname',
            'mapped_id' => 'lead.cf.customer_name',

          ],
          'telephone'         => [
            'id'        => 'telephone',
            'mapped_id' => 'lead.cf.customer_telephone',

          ],
          'payment_address_1' => [
            'id'        => 'payment_address_1',
            'mapped_id' => 'lead.cf.customer_payment_address_1',

          ],
          'payment_address_2' => [
            'id'        => 'payment_address_2',
            'mapped_id' => 'lead.cf.customer_payment_address_2',

          ],
          'comment'           => [
            'id'        => 'comment',
            'mapped_id' => 'lead.cf.customer_comment',

          ],

      ],
      'logic'           => [
          /* 'mf-checkbox' => [
             'Адвокат'           => [
               'pipeline_id' => 1731979,
               'status_id'   => 26034988,
             ],
             'Юрист з автоправа' => [
               'pipeline_id' => 1731979,
               'status_id'   => 26034988,
             ],
             'Аварійний комісар' => [
               'pipeline_id' => 1111111,
               'status_id'   => 2222222,
             ],
             'Експерт'           => [
               'pipeline_id' => 33333,
               'status_id'   => 44444,
             ],
           ],*/
      ],

      'tags' => [
        'lead'    => [
            //если есть поле с ### - то это значения из формы
            'mapping###mf-checkbox', 'test-lead', 'test-shop',
        ],
        'contact' => [
          'test-contact', 'test-shop',
        ],

      ],
    ],
  ],


];