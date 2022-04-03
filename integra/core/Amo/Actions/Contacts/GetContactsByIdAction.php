<?php

namespace Nikacrm\Core\Amo\Actions\Contacts;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Filters\FilterContactsByIdsOrderCreatedByDesc;


class GetContactsByIdAction extends AmoContactsAction

{

    protected function logic()
    {
        try {
            $with               = [];
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