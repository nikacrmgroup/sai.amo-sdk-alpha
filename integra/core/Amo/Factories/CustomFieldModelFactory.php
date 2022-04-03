<?php

namespace Nikacrm\Core\Amo\Factories;


use AmoCRM\AmoCRM\Models\CustomFieldsValues\TrackingDataCustomFieldValuesModel;
use AmoCRM\AmoCRM\Models\CustomFieldsValues\ValueCollections\TrackingDataCustomFieldValueCollection;
use AmoCRM\AmoCRM\Models\CustomFieldsValues\ValueModels\TrackingDataCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\BirthdayCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\CategoryCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\CheckboxCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\DateCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\DateTimeCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ItemsCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\LegalEntityCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultiselectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\PriceCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SmartAddressCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\StreetAddressCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextareaCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\UrlCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\BirthdayCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CategoryCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CheckboxCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\DateTimeCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\ItemsCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\LegalEntityCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultiselectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\PriceCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadiobuttonCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SmartAddressCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\StreetAddressCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextareaCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\UrlCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\BirthdayCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CategoryCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CheckboxCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\DateTimeCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\ItemsCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\LegalEntityCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultiselectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\PriceCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SmartAddressCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\StreetAdressCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextareaCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\UrlCustomFieldValueModel;
use Exception;
use http\Exception\RuntimeException;


class CustomFieldModelFactory extends AmoFactory implements ICustomFieldsTypes
{


    public function get(array $data)
    {
        $type      = $data['type'] ?? 'text';
        $enumValue = $data['enum_value'] ?? '';
        $code      = $data['code'] ?? '';
        $name      = $data['name'] ?? '';
        $id        = $data['id'] ?? 0;
        $values    = $data['values'] ?? [];


        try {
            $model = $this->getFieldModel($type);
            $model = $this->setFieldID($model, $code, $id, $name);

            $fieldCollection = $this->getFieldValueCollection($type);


            if ($type === 'multiselect') {
                $values    = explode(',', $values);
                $fieldInfo = $data['info'];
                $enums     = $fieldInfo['enums'] ?? [];

                foreach ($values as $value) {
                    foreach ($enums as $enum) {
                        if ($enum['value'] === $value) {
                            $fieldModel = $this->getFieldValueModel($type);
                            $fieldModel->setEnumId($enum['id']);
                            $fieldCollection->add(
                              $fieldModel
                            );
                            //$fieldEnumsId[] = $enum['id'];
                            //break;
                        }
                    }
                }


                $stop = 'Stop';
            } elseif ($type === 'numeric') {
                if (ctype_digit($values)) {
                    $fieldModel = $this->getFieldValueModel($type);
                    $fieldModel->setValue($values);
                    $fieldCollection->add(
                      $fieldModel);
                }
                else{
                    $stop = 'Stop';
                    return null;
                }
                //                try {
                //                    if (!is_numeric($values)) {
                //                        throw new \RuntimeException("Поле $code имеет значение не числовое: $values");
                //                    }
                //
                //                    $fieldModel = $this->getFieldValueModel($type);
                //                    $fieldModel->setValue($values);
                //                    $fieldCollection->add(
                //                      $fieldModel);
                //                } catch (\RuntimeException $e) {
                //                    $this->logException($e);
                //                }
            } else {
                $fieldModel = $this->getFieldValueModel($type);
                if ($enumValue) {
                    $fieldModel->setEnum($enumValue);
                }


                $fieldModel->setValue($values);
                $fieldCollection->add(
                  $fieldModel
                );
            }

            $model->setValues(
              $fieldCollection
            );
        } catch (Exception $e) {
            $this->logException($e);
        }


        return $model;
    }

    private function getFieldModel($type)
    {
        switch ($type) {
            case self::TYPE_TEXT:
                $model = new TextCustomFieldValuesModel();

                break;

            case self::TYPE_NUMERIC:
                $model = new NumericCustomFieldValuesModel();

                break;
            case self::TYPE_CHECKBOX:
                $model = new CheckboxCustomFieldValuesModel();

                break;
            case self::TYPE_SELECT:
                $model = new SelectCustomFieldValuesModel();

                break;
            case self::TYPE_MULTISELECT:
                $model = new MultiselectCustomFieldValuesModel();

                break;
            case self::TYPE_MULTITEXT:
                $model = new MultitextCustomFieldValuesModel();

                break;
            case self::TYPE_DATE:
                $model = new DateCustomFieldValuesModel();

                break;
            case self::TYPE_URL:
                $model = new UrlCustomFieldValuesModel();

                break;
            case self::TYPE_TEXTAREA:
                $model = new TextareaCustomFieldValuesModel();

                break;
            case self::TYPE_RADIOBUTTON:
                $model = new RadiobuttonCustomFieldValuesModel();

                break;
            case self::TYPE_STREET_ADDRESS:
                $model = new StreetAddressCustomFieldValuesModel();

                break;
            case self::TYPE_SMART_ADDRESS:
                $model = new SmartAddressCustomFieldValuesModel();

                break;
            case self::TYPE_BIRTHDAY:
                $model = new BirthdayCustomFieldValuesModel();

                break;

            case self::TYPE_LEGAL_ENTITY:
                $model = new LegalEntityCustomFieldValuesModel();

                break;

            case self::TYPE_DATE_TIME:
                $model = new DateTimeCustomFieldValuesModel();

                break;
            case self::TYPE_ITEMS:
                $model = new ItemsCustomFieldValuesModel();

                break;
            case self::TYPE_CATEGORY:
                $model = new CategoryCustomFieldValuesModel();

                break;
            case self::TYPE_PRICE:
                $model = new PriceCustomFieldValuesModel();

                break;
            case self::TYPE_TRACKING_DATA:
                $model = new TrackingDataCustomFieldValuesModel();

                break;

            default:
                throw new Exception('CustomField Type is Unknown');
        }

        return $model;
    }

    private function getFieldValueCollection($type)
    {
        switch ($type) {
            case self::TYPE_TEXT:
                $collection = new TextCustomFieldValueCollection();

                break;

            case self::TYPE_NUMERIC:
                $collection = new NumericCustomFieldValueCollection();

                break;
            case self::TYPE_CHECKBOX:
                $collection = new CheckboxCustomFieldValueCollection();

                break;
            case self::TYPE_SELECT:
                $collection = new SelectCustomFieldValueCollection();

                break;
            case self::TYPE_MULTISELECT:
                $collection = new MultiselectCustomFieldValueCollection();

                break;
            case self::TYPE_MULTITEXT:
                $collection = new MultitextCustomFieldValueCollection();

                break;
            case self::TYPE_DATE:
                $collection = new DateCustomFieldValueCollection();

                break;
            case self::TYPE_URL:
                $collection = new UrlCustomFieldValueCollection();

                break;
            case self::TYPE_TEXTAREA:
                $collection = new TextareaCustomFieldValueCollection();

                break;
            case self::TYPE_RADIOBUTTON:
                $collection = new RadiobuttonCustomFieldValueCollection();

                break;
            case self::TYPE_STREET_ADDRESS:
                $collection = new StreetAddressCustomFieldValueCollection();

                break;
            case self::TYPE_SMART_ADDRESS:
                $collection = new SmartAddressCustomFieldValueCollection();

                break;
            case self::TYPE_BIRTHDAY:
                $collection = new BirthdayCustomFieldValueCollection();

                break;

            case self::TYPE_LEGAL_ENTITY:
                $collection = new LegalEntityCustomFieldValueCollection();

                break;

            case self::TYPE_DATE_TIME:
                $collection = new DateTimeCustomFieldValueCollection();

                break;
            case self::TYPE_ITEMS:
                $collection = new ItemsCustomFieldValueCollection();

                break;
            case self::TYPE_CATEGORY:
                $collection = new CategoryCustomFieldValueCollection();

                break;
            case self::TYPE_PRICE:
                $collection = new PriceCustomFieldValueCollection();

                break;
            case self::TYPE_TRACKING_DATA:
                $collection = new TrackingDataCustomFieldValueCollection();

                break;

            default:
                throw new Exception('CustomField Type is Unknown');
        }

        return $collection;
    }

    private function getFieldValueModel($type)
    {
        switch ($type) {
            case self::TYPE_TEXT:
                $model = new TextCustomFieldValueModel();

                break;

            case self::TYPE_NUMERIC:
                $model = new NumericCustomFieldValueModel();

                break;
            case self::TYPE_CHECKBOX:
                $model = new CheckboxCustomFieldValueModel();

                break;
            case self::TYPE_SELECT:
                $model = new SelectCustomFieldValueModel();

                break;
            case self::TYPE_MULTISELECT:
                $model = new MultiselectCustomFieldValueModel();

                break;
            case self::TYPE_MULTITEXT:
                $model = new MultitextCustomFieldValueModel();

                break;
            case self::TYPE_DATE:
                $model = new DateCustomFieldValueModel();

                break;
            case self::TYPE_URL:
                $model = new UrlCustomFieldValueModel();

                break;
            case self::TYPE_TEXTAREA:
                $model = new TextareaCustomFieldValueModel();

                break;
            case self::TYPE_RADIOBUTTON:
                $model = new RadiobuttonCustomFieldValueModel();

                break;
            case self::TYPE_STREET_ADDRESS:
                $model = new StreetAdressCustomFieldValueModel();

                break;
            case self::TYPE_SMART_ADDRESS:
                $model = new SmartAddressCustomFieldValueModel();

                break;
            case self::TYPE_BIRTHDAY:
                $model = new BirthdayCustomFieldValueModel();

                break;

            case self::TYPE_LEGAL_ENTITY:
                $model = new LegalEntityCustomFieldValueModel();

                break;

            case self::TYPE_DATE_TIME:
                $model = new DateTimeCustomFieldValueModel();

                break;
            case self::TYPE_ITEMS:
                $model = new ItemsCustomFieldValueModel();

                break;
            case self::TYPE_CATEGORY:
                $model = new CategoryCustomFieldValueModel();

                break;
            case self::TYPE_PRICE:
                $model = new PriceCustomFieldValueModel();

                break;
            case self::TYPE_TRACKING_DATA:
                $model = new TrackingDataCustomFieldValueModel();

                break;

            default:
                throw new Exception('CustomField Type is Unknown');
        }

        return $model;
    }

    /**
     * @param  \AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel  $model
     * @param  string  $code
     * @param  int  $id
     * @param  string  $name
     * @return \AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel
     */
    private function setFieldID(
      BaseCustomFieldValuesModel $model,
      string $code,
      int $id,
      string $name
    ): BaseCustomFieldValuesModel {
        if ($code) {
            $model->setFieldCode($code);
        } elseif ($id) {
            $model->setFieldId($id);
        } else {
            $model->setFieldName($name);
        }

        return $model;
    }


}