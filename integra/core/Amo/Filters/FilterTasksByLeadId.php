<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\TasksFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoTasksFilter;

class FilterTasksByLeadId implements IAmoTasksFilter
{

    public function create($params = []): TasksFilter
    {
        //TODO DTO
        $orderDirection = $params['orderDirection'] ?? 'desc';
        $orderBy        = $params['orderBy'] ?? 'created_at';
        $taskTypeId     = $params['task_type_id'] ?? false;
        $isCompleted    = $params['is_completed'] ?? false;
        $entityType     = $params['entity_type'] ?? EntityTypesInterface::LEADS;
        $entityIds      = $params['entity_ids'] ?? [];

        //$ids = $params['ids'] ?? [];

        $filter = new TasksFilter();
        $filter->setLimit(250);
        //$filter->setIds($ids);
        if ($taskTypeId) {
            $filter->setTaskTypeId($taskTypeId);
        }

        $filter->setIsCompleted($isCompleted);
        $filter->setEntityType($entityType);
        $filter->setEntityIds($entityIds);
        $filter->setOrder($orderBy, $orderDirection);

        return $filter;
    }

}