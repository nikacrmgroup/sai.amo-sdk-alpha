<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\ContactsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoContactsFilter;

class FilterContactsByIdsOrderCreatedByDesc implements IAmoContactsFilter
{

    public function create($params = ['ids' => []]): ContactsFilter
    {
        $ids    = $params['ids'];
        $filter = new ContactsFilter();
        $filter->setLimit(500);
        $filter->setIds($ids);
        $filter->setOrder('created_at', 'desc');

        return $filter;
    }
}