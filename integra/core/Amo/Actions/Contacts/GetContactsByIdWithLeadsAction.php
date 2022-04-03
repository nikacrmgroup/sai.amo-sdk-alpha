<?php

namespace Nikacrm\Core\Amo\Actions\Contacts;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use Nikacrm\Core\Amo\Filters\FilterContactsByIdsOrderCreatedByDesc;


class GetContactsByIdWithLeadsAction extends AmoContactsAction

{

    protected function logic()
    {
        try {
            $with               = [ContactModel::LEADS];
            $filter             = (new FilterContactsByIdsOrderCreatedByDesc())->create($this->params);
            $contactsCollection = $this->apiContacts->get($filter, $with);
            $contactsCollection = $this->contactsService->yieldRemainingCollection($contactsCollection);

            return $contactsCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}