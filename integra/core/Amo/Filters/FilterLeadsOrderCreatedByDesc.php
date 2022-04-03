<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\LeadsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoLeadsFilter;

class FilterLeadsOrderCreatedByDesc implements IAmoLeadsFilter
{

    public function create(): LeadsFilter
    {
        $filter = new LeadsFilter();
        $filter->setLimit(500);
        $filter->setOrder('created_at', 'desc');

        return $filter;
    }
}