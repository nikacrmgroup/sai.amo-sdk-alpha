<?php

use Nikacrm\Core\Amo\Factories\ICustomFieldsTypes;

//тут можно задать значения наборы для маппинга id полей и id полей в амо

//ФИО - 1774627
//Емейл - такого поля не существует у нас на сайте при заказе
//Телефон - 1774633
//Метод оплаты - это в амо в поля нигде не записывается
//Метод Доставки - точно так же таких полей нету под это
//Адрес, отделение - 1774631
//Город - 1774629
//Поля для коментарий нету - все что написано в коментариях нужно отправить в Амо в «Примечания»
//Теги не нужно
//


return [

    //ФИО - 1774627
  'lead.cf.customer_name'              => [
    'id' => 1774627,

  ],
    //Телефон - 1774633
  'lead.cf.customer_telephone'         => [
    'id' => 1774633,

  ],
  'lead.cf.name'                       => [
    'id' => 694799,

  ],
    //Адрес, отделение - 1774631
  'lead.cf.customer_payment_address_1' => [
    'id' => 1774631,
  ],
    //Город - 1774629
  'lead.cf.customer_payment_city' => [
    'id' => 1774629,
  ],

  'contact_email'            => [
    'code' => 'CONTACTS.EMAIL.WORK',
  ],
  'company_email'            => [
    'code' => 'COMPANIES.EMAIL.WORK',
  ],
  'contact_phone_work'       => [
    'code' => 'CONTACTS.PHONE.WORK',
  ],
  'contact_phone_home'       => [
    'code' => 'CONTACTS.PHONE.HOME',
  ],
  'contact_phone_mobile'     => [
    'code' => 'CONTACTS.PHONE.MOB',
  ],
  'company_phone_work'       => [
    'code' => 'COMPANIES.PHONE.WORK',
  ],
  'company_phone_home'       => [
    'code' => 'COMPANIES.PHONE.HOME',
  ],
  'company_phone_mobile'     => [
    'code' => 'COMPANIES.PHONE.MOB',
  ],


  //'system'        => ICustomFieldsTypes::SYSTEM_FIELDS,


];