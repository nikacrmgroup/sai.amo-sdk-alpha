<?php

namespace Nikacrm\Core\Amo\Actions\CustomFields;

use AmoCRM\Collections\CustomFieldsValuesCollection;


class PrepareCustomFieldsCollectionAction extends AmoCustomFieldsAction

{


    protected function logic()
    {
        $customFieldsData       = $this->params['custom_fields'] ?? [];
        $customFieldsCollection = new CustomFieldsValuesCollection;

        try {
            foreach ($customFieldsData as $customField) {
                $customFieldModel = (new PrepareCustomFieldsModelAction())->exec(['field' => $customField]);
                if ($customFieldModel) {
                    $customFieldsCollection->add(
                      $customFieldModel
                    );
                }
            }

            return $customFieldsCollection;
        } catch (\Throwable $e) {
            $this->logException($e);
            //die;
        }
    }

}