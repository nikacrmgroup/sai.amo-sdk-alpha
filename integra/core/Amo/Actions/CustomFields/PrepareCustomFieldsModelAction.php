<?php

namespace Nikacrm\Core\Amo\Actions\CustomFields;

use AmoCRM\Exceptions\AmoCRMApiException;

use Nikacrm\Core\Amo\Factories\CustomFieldModelFactory;


class PrepareCustomFieldsModelAction extends AmoCustomFieldsAction

{


    protected function logic()
    {
        //$customFieldType = $this->params['field']['type'] ?? 'text';
        $customField     = $this->params['field'];
        try {
            $customFieldModel = (new CustomFieldModelFactory())->get( $customField);
            $stop             = 'Stop';

            return $customFieldModel;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}