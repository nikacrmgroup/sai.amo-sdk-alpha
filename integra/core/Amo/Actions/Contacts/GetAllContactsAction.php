<?php

namespace Nikacrm\Core\Amo\Actions\Contacts;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Filters\FilterAllContactsOrderCreatedByDesc;
use Nikacrm\Core\Amo\Filters\FilterAllContactsOrderUpdatedByDesc;
use Nikacrm\Core\Amo\Filters\FilterLeadsOrderCreatedByDesc;
use Nikacrm\Core\Container;

//TODO переименовать в AGetAllContacts
class GetAllContactsAction extends AmoContactsAction

{
    protected const DEFAULT_TTL = 5 * 60; //5 минут
    public function fetchData()
    {
        if (isset($this->params['orderBy']) && $this->params['orderBy'] === 'updated_at') {
            $filter = (new FilterAllContactsOrderUpdatedByDesc())->create();
        } else {
            $filter = (new FilterAllContactsOrderCreatedByDesc())->create();
        }
        $contactsCollection = $this->apiContacts->get($filter);
        $contactsCollection = $this->contactsService->yieldRemainingCollection($contactsCollection);

        return $contactsCollection;
    }

    protected function logic()
    {
        try {

            /* @var \Nikacrm\Core\Cache $cache */
            $cache = Container::get('cache');

            return $cache->getCachedData("all_contacts", [$this, 'fetchData'], self::DEFAULT_TTL);


        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}