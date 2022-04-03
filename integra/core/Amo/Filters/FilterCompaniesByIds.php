<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\CompaniesFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoCompaniesFilter;

class FilterCompaniesByIds implements IAmoCompaniesFilter
{

    public function create($params = ['ids' => []]): CompaniesFilter
    {
        $ids    = $params['ids'];
        $filter = new CompaniesFilter();
        $filter->setLimit(250);
        $filter->setIds($ids);
        $filter->setOrder('created_at', 'desc');

        return $filter;
    }
}