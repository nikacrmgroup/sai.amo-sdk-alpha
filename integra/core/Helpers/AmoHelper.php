<?php

namespace Nikacrm\Core\Helpers;


use Nikacrm\Core\Container;
use Nikacrm\Core\Config;
use Nikacrm\Core\Request;

class AmoHelper
{

    private $amo;
    private $request;

    public function __construct(array $request, $amo, Config $config)
    {
        $this->config  = Container::get('config');
        $this->request = $request;
        $this->amo     = $amo;
    }

    /**Получить значение поля лида по названию
     * @param $field_name
     * @param  array  $customFields
     * @return bool|mixed
     */
    public static function getFieldValueByName($field_name, array $customFields)
    {
        $field_value = '';

        foreach ($customFields as $field) {
            if ($field->name === $field_name) {
                $field_value = $field->values[0];
                $field_value = $field_value->value ?? $field_value;
                break;
            }
        }


        return $field_value;
    }

    /**
     * @param  string  $type  тип проверки дубля
     * @param $entity
     * @return bool
     */
    public function isEntityDouble($type, $entity): bool
    {
        $result = false;
        switch ($type) {
            case 'phone':
                $result = $this->_isDoublePhone($entity);
                break;

            case 'email':
                $result = $this->_isDoubleEmail($entity);
                break;

            case 'any':
                $result = $this->_isDoubleAny($entity);
                break;

            case 'both':
                $result = $this->_isDoubleBoth($entity);
                break;

            default:
                return $result;
        }

        return $result;
    }

    private function _isDoubleAny($entity)
    {
        /*Проверяем, есть ли дубль по телефону, если он указан, иначе email, а если мыла нет - то false*/
        $doublePhone = $this->_isDoublePhone($entity);
        $doubleEmail = $this->_isDoubleEmail($entity);
        if ($doublePhone) {
            return $doublePhone;
        }

        return $doubleEmail;
    }

    public static function getEntityByCfValueAndId(array $entities, int $cfId, $cfValue): array
    {
        $entitiesFound = [];
        foreach ($entities as $entity) {
            if ($entity['custom_fields_values']) {
                $customFields = $entity['custom_fields_values'];
                foreach ($customFields as $customField) {
                    if (isset($customField['field_id'])) {
                        $fieldId = $customField['field_id'] ?? 0;
                        if ($fieldId === $cfId) {
                            $fieldValue = $customField['values'][0]['value'] ?? null;
                            if ($fieldValue === $cfValue) {
                                $entitiesFound[$entity['id']] = $entity;
                            }
                        }
                    }
                }
            }
        }

        return $entitiesFound;
    }

    public static function getEntityByCfValueAndCode(array $entities, string $cfCode, $cfValue): array
    {
        //TODO enum
        $entitiesFound = [];
        foreach ($entities as $entity) {
            if ($entity['custom_fields_values']) {
                $customFields = $entity['custom_fields_values'];
                foreach ($customFields as $customField) {
                    if (isset($customField['field_id'])) {
                        $fieldCode = $customField['field_code'] ?? '';
                        if ($fieldCode === $cfCode) {
                            $fieldValue = $customField['values'][0]['value'] ?? null;
                            if ($fieldValue === $cfValue) {
                                $entitiesFound[$entity['id']] = $entity;
                            }
                        }
                    }
                }
            }
        }

        return $entitiesFound;
    }

    public static function getEntityByCfValueAndName(array $entities, string $cfName, $cfValue): array
    {
        $entitiesFound = [];
        foreach ($entities as $entity) {
            if ($entity['custom_fields_values']) {
                $customFields = $entity['custom_fields_values'];
                foreach ($customFields as $customField) {
                    if (isset($customField['field_id'])) {
                        $fieldName = $customField['field_name'] ?? '';
                        if ($fieldName === $cfName) {
                            $fieldValue = $customField['values'][0]['value'] ?? null;
                            if ($fieldValue === $cfValue) {
                                $entitiesFound[$entity['id']] = $entity;
                            }
                        }
                    }
                }
            }
        }

        return $entitiesFound;
    }

    private function _isDoubleBoth($entity)
    {
        $email        = $this->getEmail($this->request, $entity);
        $phone        = $this->getPhone($this->request, $entity);
        $entityDouble = false;
        if ($email && $phone) {
            try {
                if ($entity === 'contact') {
                    $entitiesPhones = $this->amo->contacts()->searchByPhone($phone);
                } else {
                    $entitiesPhones = $this->amo->companies()->searchByPhone($phone);
                }

                foreach ($entitiesPhones as $entityPhone) {
                    $emails = $entityPhone->cf('Email')->getValues();
                    /*Получаем все email по контакту или компании, и если среди них есть и нужный нам email - то это
                    дубль*/
                    if (isset($emails) && in_array($email, $emails, true)) {
                        $entityDouble = $entityPhone;
                        break;
                    }
                }

                return $entityDouble;
            } catch (\Exception $e) {
                //todo write to Logger
            }
        }

        return false;
    }

    private function _isDoubleEmail($entity)
    {
        if ($email = $this->getEmail($this->request['POST'], $entity)) {
            try {
                if ($entity === 'contact') {
                    return $this->amo->contacts()->searchByEmail($email)->first();
                }

                return $this->amo->companies()->searchByEmail($email)->first();
            } catch (\Exception $e) {
            }
        }

        return false;
    }

    private function _isDoublePhone($entity)
    {
        if ($phone = $this->getPhone($this->request['POST'], $entity)) {
            try {
                if ($entity === 'contact') {
                    return $this->amo->contacts()->searchByPhone($phone)->first();
                }

                return $this->amo->companies()->searchByPhone($phone)->first();
            } catch (\Exception $e) {
            }
        }

        return false;
    }

    private function getEmail($request, $entity = 'contact')
    {
        $request_field = $entity.'_email';

        return (isset($request[$request_field]) && $request[$request_field] !== '') ? $request[$request_field] :
          false;
    }

    private function getPhone($request, $entity = 'contact')
    {
        $request_field = $entity.'_phone';

        return (isset($request[$request_field]) && $request[$request_field] !== '') ? $request[$request_field] :
          false;
    }

}