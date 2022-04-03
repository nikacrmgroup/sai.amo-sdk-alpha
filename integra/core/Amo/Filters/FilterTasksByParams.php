<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\TasksFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoTasksFilter;

class FilterTasksByParams implements IAmoTasksFilter
{

    public function create($params = []): TasksFilter
    {
        //TODO DTO
        $orderDirection = $params['orderDirection'] ?? 'desc';
        $orderBy        = $params['orderBy'] ?? 'created_at';
        $taskTypeId     = $params['task_type_id'] ?? false;
        $isCompleted    = $params['is_completed'] ?? false;
        $userIds        = $params['user_ids'] ?? [];
        $entityType     = $params['entity_type'] ?? false;


        $filter = new TasksFilter();
        $filter->setLimit(250);
        if ($taskTypeId) {
            $filter->setTaskTypeId($taskTypeId);
        }
        $filter->setIsCompleted($isCompleted);
        $filter->setResponsibleUserId($userIds);
        if ($entityType) {
            $filter->setEntityType($entityType);
        }

        $filter->setOrder($orderBy, $orderDirection);

        return $filter;
    }

}