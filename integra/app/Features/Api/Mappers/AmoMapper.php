<?php

namespace Nikacrm\App\Features\Api\Mappers;


use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Actions\CustomFields\GetCustomFieldsByEntityAction;
use Nikacrm\Core\Amo\Base\AmoDTO;
use Nikacrm\Core\Amo\DTO\CompanyDTO;
use Nikacrm\Core\Amo\DTO\ContactDTO;
use Nikacrm\Core\Amo\DTO\LeadComplexDTO;
use Nikacrm\Core\Amo\DTO\LeadDTO;
use Nikacrm\Core\Amo\Factories\ICustomFieldsTypes;
use Nikacrm\Core\Container;
use RuntimeException;

class AmoMapper extends BaseMapper
{

    private array $amoConfig;
    private array $formConfig;

    public function __construct()
    {
        parent::__construct();

        $this->amoConfig = $this->config->getAmoConfig();
    }

    public function prepareDto(array $request, string $type): AmoDTO
    {
        $this->formConfig = $this->getFormConfigById($request);
        switch ($type) {
            case 'lead_complex':
                return $this->prepareLeadComplexDTO($request);

            case 'lead':
                return $this->prepareLeadDTO($request);

            default:
                break;
        }
    }

    private function prepareLeadComplexDTO($request)
    {
        $leadComplexDto = new LeadComplexDTO();
        $contactDto     = new ContactDTO();


        $formConfig = $this->formConfig;

        $entitiesToCreate = $formConfig['entities_create'] ?? [];
        $amoFields        = $this->getAmoFields($request['fields'], $formConfig);


        $leadComplexDto->setCustomFields($amoFields['custom_fields']['leads'] ?? []);
        $leadComplexDto->setLeadName($this->prepareLeadName($amoFields['system_fields'] ?? [], $formConfig));

        $leadComplexDto->setStatusId($this->prepareStatusId($formConfig, $request));
        $leadComplexDto->setPipelineId($this->preparePipelineId($formConfig, $request));
        $leadComplexDto->setTags($this->prepareTags($formConfig, $request, 'lead'));

        $contactDto->setName($this->prepareContactName($amoFields['system_fields'] ?? ''));
        $firstName  = $amoFields['system_fields']['contact_first_name'] ?? '';
        $secondName = $amoFields['system_fields']['contact_second_name'] ?? '';
        if ($secondName) {
            $firstName .= ' '.$secondName;
        }
        $contactDto->setFirstName($firstName);
        $contactDto->setLastName($amoFields['system_fields']['contact_last_name'] ?? '');
        $contactDto->setTags($this->prepareTags($formConfig, $request, 'contact'));
        $contactDto->setCustomFields($amoFields['custom_fields']['contacts'] ?? []);
        $leadComplexDto->setContactDto($contactDto);

        /*Проверяем, нужна ли компания*/
        if (in_array('company', $entitiesToCreate, true)) {
            $companyDto = new CompanyDTO();

            $companyDto->setName($this->prepareCompanyName($amoFields['system_fields'] ?? ''));
            $companyDto->setTags($this->prepareTags($formConfig, $request, 'contact'));
            $companyDto->setCustomFields($amoFields['custom_fields']['companies'] ?? []);


            $leadComplexDto->setCompanyDto($companyDto);
        }

        return $leadComplexDto;
    }


    private function prepareLeadDTO($request) : LeadDTO
    {
        $leadDto = new LeadDTO();

        $formConfig = $this->formConfig;

        $entitiesToCreate = $formConfig['entities_create'] ?? [];
        $amoFields        = $this->getAmoFields($request['fields'], $formConfig);

        $leadDto->setCustomFields($amoFields['custom_fields']['leads'] ?? []);
        $leadDto->setLeadName($this->prepareLeadName($amoFields['system_fields'] ?? [], $formConfig));

        $leadDto->setStatusId($this->prepareStatusId($formConfig, $request));
        $leadDto->setPipelineId($this->preparePipelineId($formConfig, $request));
        $leadDto->setTags($this->prepareTags($formConfig, $request, 'lead'));

        return $leadDto;
    }

    /**
     * @param $form_id
     * @return mixed
     */
    private function getFormConfigById($request)
    {
        $formId     = $request['meta']['form_id'] ?? 'default';
        $formConfig = $this->getFormConfig($formId);

        return $formConfig;
    }

    /**
     * @param  array  $fields
     * @param  array  $mapper
     * @return array
     */
    private function getAmoFields(array $fields, array $formConfig): array
    {
        $json = je($fields);

        /*Если есть маппинг в форме - берем оттуда, если нет - то из дефолта*/
        $formFieldsMapper = $formConfig['mapping'] ?? $this->requestMapper->getAll();

        $amoFieldsMapper = $this->amoFieldsMapper->getAll();
        $amoSystemFields = ICustomFieldsTypes::SYSTEM_FIELDS;
        $amoFields       = [
          'custom_fields' => [],
          'system_fields' => [],
        ];

        $amoCustomFields = $this->prepareAmoLeadContactCompanyFields();
        //$amoCustomFields = $this->prepareAmoCustomFields($mergedFields);


        foreach ($fields as $id => $fieldData) {
            if (isset($formFieldsMapper[$id])) {
                $mappedIds = $formFieldsMapper[$id]['mapped_id'];
                /*Проверяем, если маппинг не массив значений, приводим к нему*/
                if (!is_array($mappedIds)) {
                    $mappedIds = [$mappedIds];
                }
                foreach ($mappedIds as $mappedId) {
                    if (in_array($mappedId, $amoSystemFields, true)) {
                        $amoFields['system_fields'][$mappedId] = $fieldData;
                    } else {
                        $field = [
                          'id'   => $amoFieldsMapper[$mappedId]['id'] ?? false,
                          'name' => $amoFieldsMapper[$mappedId]['name'] ?? false,
                          'code' => $amoFieldsMapper[$mappedId]['code'] ?? false,
                        ];

                        $field['values'] = $fieldData;

                        $fieldInfo = $this->getFieldInfo($amoCustomFields, $field, $mappedId);
                        if (!$fieldInfo) {
                            throw new RuntimeException('Нет такого поля! '.je($field).' $mappedId: '.$mappedId /*.
                         ' $amoCustomFields '.je($amoCustomFields)*/);
                        }
                        $entityType = $fieldInfo['entity_type'];
                        /*Проверяем, является ли поле составным enum*/
                        if (isset($fieldInfo['enum_value'])) {
                            $field['enum_value'] = $fieldInfo['enum_value'];
                        }
                        if (isset($fieldInfo['code'])) {
                            $field['code'] = $fieldInfo['code'];
                        }
                        $field['type'] = $fieldInfo['type'];
                        $field['name'] = $fieldInfo['name'];
                        $field['id']   = $fieldInfo['id']; //переопределяем id если поиск по имени
                        $field['info'] = $fieldInfo;

                        $amoFields['custom_fields'][$entityType][$mappedId] = $field;
                    }
                }
            } else {
                //todo лог игнора?
            }
        }
        //добавим, если есть, кастомные поля из конфига
        $amoFields = $this->addFieldsValuesFromConfig($amoFields, $formConfig, $amoCustomFields, $amoFieldsMapper);

        return $amoFields;
    }

    private function prepareLeadName(array $amoData, array $formConfig): string
    {
        return $amoData['lead_name'] ?? $formConfig['lead_name'] ?? '';
    }

    private function prepareStatusId(array $formConfig, array $request): int
    {
        $statusId = $this->getOverrideLogic('status_id', $formConfig, $request) ?? $formConfig['status_id'] ?? 0;

        return $statusId;
    }

    private function preparePipelineId(array $formConfig, array $request): int
    {
        $pipelineId = $this->getOverrideLogic('pipeline_id', $formConfig, $request) ?? $formConfig['pipeline_id'] ?? 0;

        return $pipelineId;
    }

    private function prepareTags(array $formConfig, array $request, $entityType = 'lead'): array
    {
        $tags = $formConfig['tags'][$entityType] ?? [];
        /*Ищем среди конфига такой формат: mapping###имя_поля_формы. Если есть, то добавляем значение поля в массив
        тегов*/
        $preparedTags = [];
        foreach ($tags as $tag) {
            if (str_contains($tag, 'mapping###')) {
                $fieldName         = str_replace('mapping###', '', $tag);
                $requestFieldValue = $request['fields'][$fieldName] ?? [];
                if ($requestFieldValue) {
                    /*Проверяем, не содержит ли строка запятые. если да - то делаем из нее массив*/
                    if (str_contains($requestFieldValue, ',')) {
                        $requestFieldValueArray = explode(',', $requestFieldValue);
                        foreach ($requestFieldValueArray as $fieldTag) {
                            if ($fieldTag) {
                                $preparedTags[] = $fieldTag;
                            }
                        }
                    } else {
                        $preparedTags[] = $requestFieldValue;
                    }
                }
            } else {
                $preparedTags[] = $tag;
            }
        }


        return $preparedTags;
    }

    private function prepareContactName(array $amoData): string
    {
        $config = $this->amoConfig;

        return $amoData['contact_name'] ?? $config['contact_name'] ?? '';
    }

    private function prepareCompanyName(array $amoData): string
    {
        $config = $this->amoConfig;

        return $amoData['company_name'] ?? $config['company_name'] ?? '';
    }

    private function getFormConfig(string $formId)
    {
        $allFormsConfig = $this->config->forms;
        $formConfig     = $allFormsConfig[$formId] ?? $allFormsConfig['default'];

        return $formConfig;
    }

    /**
     * @return array
     */
    private function prepareAmoLeadContactCompanyFields(): array
    {
        $leadFieldsCollection    = (new GetCustomFieldsByEntityAction(EntityTypesInterface::LEADS))->exec();
        $contactFieldsCollection = (new GetCustomFieldsByEntityAction(EntityTypesInterface::CONTACTS))->exec();
        $companyFieldsCollection = (new GetCustomFieldsByEntityAction(EntityTypesInterface::COMPANIES))->exec();

        $leadFields    = $leadFieldsCollection->toArray();
        $contactFields = $contactFieldsCollection->toArray();
        $companyFields = $companyFieldsCollection->toArray();

        //$mergedFields = array_merge($leadFields, $contactFields, $companyFields);
        $mergedFields = [
          'leads'     => $this->prepareAmoCustomFields($leadFields),
          'contacts'  => $this->prepareAmoCustomFields($contactFields),
          'companies' => $this->prepareAmoCustomFields($companyFields),
        ];

        return $mergedFields;
    }

    private function getFieldInfo(array $amoFields, array $field, string $mappedId): array
    {
        /*Определяем, если есть code, какая entity нужна для поля*/

        if (isset($field['code']) && $field['code']) {
            $code = $field['code'];
            /*Проверяем, не enum, ли это или?*/
            if (str_contains($code, '.')) {
                $codeArr    = explode('.', $code);
                $codeEntity = $codeArr[0] ?? '';
                $codeMain   = $codeArr[1] ?? '';
                $codeEnum   = $codeArr[2] ?? '';
                /*Берем данные из нужных полей. Entity приходит в верхнем регистре, уменьшаем его для поиска по
                массиву*/
                $codeEntity   = mb_strtolower($codeEntity);
                $entityFields = $amoFields[$codeEntity];

                if ($codeEnum) {
                    $fieldInfo = $entityFields[$codeMain] ?? [];
                    if ($fieldInfo) {
                        $fieldInfo['enum_value'] = $codeEnum;
                    }

                    return $fieldInfo;
                }

                return $amoFields[$codeEntity][$field['code']] ?? [];
            }
        }
        /*Ищем по id*/
        if ($field['id']) {
            $fieldId = $field['id'];

            return $amoFields['leads'][$fieldId] ?? $amoFields['contacts'][$fieldId]
              ?? $amoFields['companies'][$fieldId]
              ?? [];
        }
        /*Ищем по имени*/
        $fieldName = $field['name'];
        $result    = [];
        foreach ($amoFields as $entityFields) {
            foreach ($entityFields as $entityField) {
                if ($entityField['name'] === $fieldName) {
                    $result = $entityField;
                    break 2;
                }
            }
        }

        return $result;
    }

    private function addFieldsValuesFromConfig(array $amoFields, array $formConfig, $amoCustomFields, $amoFieldsMapper)
    {
        $stop             = 'Stop';
        $alteredAmoFields = $amoFields;
        foreach ($formConfig['mapping'] as $formConfigName => $formConfigValue) {
            if (str_contains($formConfigName, 'custom###')) {
                $mappedId = $formConfigValue['mapped_id'];

                $field = [
                  'id'   => $amoFieldsMapper[$mappedId]['id'] ?? false,
                  'name' => $amoFieldsMapper[$mappedId]['name'] ?? false,
                  'code' => $amoFieldsMapper[$mappedId]['code'] ?? false,
                ];

                $field['values'] = $formConfigValue['value'];
                $fieldValue      = $formConfigValue['value'];
                /*Проверяем, не брать ли значение из entity*/
                if (str_contains($fieldValue, 'entity###')) {
                    $entityValue  = str_replace('entity###', '', $fieldValue);
                    $systemFields = $amoFields['system_fields'] ?? [];

                    switch ($entityValue) {
                        case 'contact_name':

                            $contactName     = $this->getContactNameFromSystemFields($systemFields);
                            $field['values'] = trim($contactName);
                            //Container::get('app_logger')->save('$field[ ]: '. je($field['values']));
                            break;

                        case 'company_name':

                            $companyName     = $this->getCompanyNameFromSystemFields($systemFields);
                            $field['values'] = trim($companyName);
                            //Container::get('app_logger')->save('$field[ ]: '. je($field['values']));
                            break;
                        default:
                            break;
                    }
                }
                $fieldInfo = $this->getFieldInfo($amoCustomFields, $field, $mappedId);
                if (!$fieldInfo) {
                    throw new RuntimeException('Нет такого поля! '.je($field).' $mappedId: '.$mappedId /*.
                         ' $amoCustomFields '.je($amoCustomFields)*/);
                }
                $entityType = $fieldInfo['entity_type'];
                /*Проверяем, является ли поле составным enum*/
                if (isset($fieldInfo['enum_value'])) {
                    $field['enum_value'] = $fieldInfo['enum_value'];
                }
                if (isset($fieldInfo['code'])) {
                    $field['code'] = $fieldInfo['code'];
                }
                $field['type'] = $fieldInfo['type'];
                $field['name'] = $fieldInfo['name'];
                $field['id']   = $fieldInfo['id']; //переопределяем id если поиск по имени
                $field['info'] = $fieldInfo;

                $alteredAmoFields['custom_fields'][$entityType][$mappedId] = $field;
            }
        }

        return $alteredAmoFields;
    }

    private function getOverrideLogic(string $option, array $formConfig, array $request)
    {
        $logic = $formConfig['logic'] ?? [];
        if (!$logic) {
            return null;
        }

        $result = null;

        foreach ($request['fields'] as $requestFieldName => $requestFieldValue) {
            if (isset($logic[$requestFieldName])) {
                $fieldLogic = $logic[$requestFieldName];

                /*Проверяем, не множественное ли это значение*/
                if (str_contains($requestFieldValue, ',')) {
                    $values = explode(',', $requestFieldValue);

                    foreach ($values as $value) {
                        if (isset($fieldLogic[$value][$option])) {
                            $result = $fieldLogic[$value][$option];
                        }
                    }
                } else {
                    if (isset($fieldLogic[$requestFieldValue][$option])) {
                        $result = $fieldLogic[$requestFieldValue][$option];
                    }
                }
            }
        }

        return $result;
    }

    private function prepareAmoCustomFields(array $rawFields)
    {
        $preparedFields = [];
        foreach ($rawFields as $field) {
            if (isset($field['code'])) {
                $preparedFields[$field['code']] = $field;
            } else {
                $preparedFields[$field['id']] = $field;
            }
        }

        return $preparedFields;
    }

    private function getContactNameFromSystemFields($systemFields)
    {
        $contactFirstName  = ($systemFields['contact_first_name'] ?? '');
        $contactSecondName = ($systemFields['contact_second_name'] ?? '');
        $contactLastName   = ($systemFields['contact_last_name'] ?? '');

        if ($contactLastName) {
            $contactLastName .= ' ';
        }

        if ($contactFirstName) {
            $contactFirstName .= ' ';
        }

        $contactName = $contactLastName.$contactFirstName.$contactSecondName;

        return $contactName;
    }

    private function getCompanyNameFromSystemFields($systemFields)
    {
        $companyName = ($systemFields['company_name'] ?? '');


        return $companyName;
    }


}