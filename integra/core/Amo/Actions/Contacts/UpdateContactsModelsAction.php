<?php

namespace Nikacrm\Core\Amo\Actions\Contacts;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Filters\FilterContactsByIdsOrderCreatedByDesc;


class UpdateContactsModelsAction extends AmoContactsAction

{

    protected function logic()
    {
        $contactsModels     = $this->params['models'] ?? [];
        $contactsCollection = new ContactsCollection();
        try {
            foreach ($contactsModels as $model) {
                $contactsCollection->add($model);
            }

            //TODO Note!!!
            $result = $this->apiContacts->update($contactsCollection);

            //$result = [];

            return $result;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }
}