<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\LeadsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoLeadsFilter;
use Nikacrm\Core\Amo\Tasks\Pipelines\TaskGetAllPipelines;

class FilterLeadsByParams implements IAmoLeadsFilter
{

    public function create($params = []): LeadsFilter
    {
        $orderDirection = $params['order'] ?? 'desc';
        $statuses       = $params['statuses'] ?? [];
        $orderBy        = $params['orderBy'] ?? 'created_at';
        $filter         = new LeadsFilter();
        $filter->setLimit(500);
        //TODO статус не работает!!!
        $filter->setStatuses($statuses);
        $filter->setOrder($orderBy, $orderDirection);

        return $filter;
    }

}