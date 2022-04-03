<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\LeadsFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoLeadsFilter;
use Nikacrm\Core\Amo\Tasks\Pipelines\TaskGetAllPipelines;

class FilterLeadsByIds implements IAmoLeadsFilter
{

    public function create($params = []): LeadsFilter
    {
        $orderDirection = $params['orderDirection'] ?? 'desc';
        $orderBy        = $params['orderBy'] ?? 'created_at';

        $ids = $params['ids'] ?? [];

        $filter = new LeadsFilter();
        $filter->setLimit(500);
        $filter->setIds($ids);
        $filter->setOrder($orderBy, $orderDirection);

        return $filter;
    }

}