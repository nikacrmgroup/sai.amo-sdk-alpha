<?php

use Nikacrm\Core\Amo\Factories\ICustomFieldsTypes;

//тут можно задать значения наборы для маппинга id полей и id полей в амо


return [

  'lead.cf.customer_name'              => [
    'id' => 694799,

  ],
  'lead.cf.customer_telephone'         => [
    'id' => 680849,

  ],
  'lead.cf.name'                       => [
    'id' => 694799,

  ],
  'contact.cf.gorod'                   => [
    'id' => 694769,
  ],
  'lead.cf.customer_payment_address_1' => [
    'id' => 685733,
  ],
  'lead.cf.customer_payment_address_2' => [
    'id' => 696511,
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