<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\TasksFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoTasksFilter;

class FilterTasksByCompletedResponsibleId implements IAmoTasksFilter
{

    public function create($params = []): TasksFilter
    {
        //TODO DTO
        $orderDirection = $params['orderDirection'] ?? 'desc';
        $orderBy        = $params['orderBy'] ?? 'created_at';
        $isCompleted    = $params['is_completed'] ?? false;
        $userIds        = $params['user_ids'] ?? [];


        $filter = new TasksFilter();
        $filter->setLimit(250);
        $filter->setIsCompleted($isCompleted);
        $filter->setResponsibleUserId($userIds);

        $filter->setOrder($orderBy, $orderDirection);

        return $filter;
    }

}