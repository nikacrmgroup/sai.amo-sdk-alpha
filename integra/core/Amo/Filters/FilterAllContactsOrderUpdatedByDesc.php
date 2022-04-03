<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\ContactsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoContactsFilter;

class FilterAllContactsOrderUpdatedByDesc implements IAmoContactsFilter
{

    public function create(): ContactsFilter
    {
        $filter = new ContactsFilter();
        $filter->setLimit(500);
        $filter->setOrder('updated_at', 'desc');

        return $filter;
    }
}