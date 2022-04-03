<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\ContactsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoContactsFilter;

class FilterAllContactsOrderCreatedByDesc implements IAmoContactsFilter
{

    public function create(): ContactsFilter
    {
        $filter = new ContactsFilter();
        $filter->setLimit(500);
        $filter->setOrder('created_at', 'desc');

        return $filter;
    }
}