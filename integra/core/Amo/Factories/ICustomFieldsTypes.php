<?php

namespace Nikacrm\Core\Amo\Factories;

interface ICustomFieldsTypes
{

    public const TYPE_TEXT           = 'text'; //
    public const TYPE_NUMERIC        = 'numeric'; //
    public const TYPE_CHECKBOX       = 'checkbox'; //
    public const TYPE_SELECT         = 'select'; //
    public const TYPE_MULTISELECT    = 'multiselect'; //
    public const TYPE_MULTITEXT      = 'multitext'; //
    public const TYPE_DATE           = 'date'; //
    public const TYPE_URL            = 'url'; //
    public const TYPE_TEXTAREA       = 'textarea'; //
    public const TYPE_RADIOBUTTON    = 'radiobutton'; //
    public const TYPE_STREET_ADDRESS = 'streetaddress'; //
    public const TYPE_SMART_ADDRESS  = 'smart_address'; //
    public const TYPE_BIRTHDAY       = 'birthday'; //
    public const TYPE_LEGAL_ENTITY   = 'legal_entity'; //
    public const TYPE_DATE_TIME      = 'date_time'; //
    public const TYPE_ITEMS          = 'items'; //
    public const TYPE_CATEGORY       = 'category'; //
    public const TYPE_PRICE          = 'price'; //
    public const TYPE_TRACKING_DATA  = 'tracking_data'; //


    public const SYSTEM_TYPE_LEAD_NAME          = 'lead_name';
    public const SYSTEM_TYPE_LEAD_PRICE         = 'lead_price';
    public const SYSTEM_TYPE_CONTACT_FIRST_NAME = 'contact_first_name';
    public const SYSTEM_TYPE_CONTACT_LAST_NAME  = 'contact_last_name';
    public const SYSTEM_TYPE_CONTACT_SECOND_NAME  = 'contact_second_name';
    public const SYSTEM_TYPE_CONTACT_NAME       = 'contact_name';
    //public const SYSTEM_TYPE_CONTACT_EMAIL        = 'contact_email';
    //public const SYSTEM_TYPE_CONTACT_PHONE_WORK   = 'contact_phone_work';
    //public const SYSTEM_TYPE_CONTACT_PHONE_HOME   = 'contact_phone_home';
    //public const SYSTEM_TYPE_CONTACT_PHONE_MOBILE = 'contact_phone_mobile';
    public const SYSTEM_TYPE_COMPANY_NAME = 'company_name';

    public const CUSTOM_FIELDS_TYPES = [
      self::TYPE_TEXT,
      self::TYPE_NUMERIC,
      self::TYPE_CHECKBOX,
      self::TYPE_SELECT,
      self::TYPE_MULTISELECT,
      self::TYPE_MULTITEXT,
      self::TYPE_DATE,
      self::TYPE_URL,
      self::TYPE_TEXTAREA,
      self::TYPE_RADIOBUTTON,
      self::TYPE_STREET_ADDRESS,
      self::TYPE_SMART_ADDRESS,
      self::TYPE_BIRTHDAY,
      self::TYPE_LEGAL_ENTITY,
      self::TYPE_DATE_TIME,
      self::TYPE_ITEMS,
      self::TYPE_CATEGORY,
      self::TYPE_PRICE,
      self::TYPE_TRACKING_DATA,

    ];

    public const SYSTEM_FIELDS = [

      self::SYSTEM_TYPE_LEAD_NAME,
      self::SYSTEM_TYPE_LEAD_PRICE,
      self::SYSTEM_TYPE_CONTACT_FIRST_NAME,
      self::SYSTEM_TYPE_CONTACT_LAST_NAME,
      self::SYSTEM_TYPE_CONTACT_SECOND_NAME,
      self::SYSTEM_TYPE_CONTACT_NAME,
      //self::SYSTEM_TYPE_CONTACT_EMAIL,
      self::SYSTEM_TYPE_COMPANY_NAME,
      //self::SYSTEM_TYPE_CONTACT_PHONE_WORK,
      //self::SYSTEM_TYPE_CONTACT_PHONE_HOME,
      //self::SYSTEM_TYPE_CONTACT_PHONE_MOBILE,
    ];


}