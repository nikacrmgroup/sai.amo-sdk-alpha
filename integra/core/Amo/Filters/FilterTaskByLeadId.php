<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\TasksFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\Interfaces\AmoTaskFilterInterface;

class FilterTaskByLeadId implements AmoTaskFilterInterface
{

    public function create($params = []): TasksFilter
    {
        //TODO DTO
        $orderDirection = $params['orderDirection'] ?? 'desc';
        $orderBy        = $params['orderBy'] ?? 'created_at';
        $taskTypeId     = $params['taskTypeId'] ?? '';
        $isCompleted    = $params['isCompleted'] ?? false;
        $entityType     = $params['entity_type'] ?? EntityTypesInterface::LEADS;
        $entityIds      = $params['entity_ids'] ?? [];

        //$ids = $params['ids'] ?? [];

        $filter = new TasksFilter();
        $filter->setLimit(250);
        //$filter->setIds($ids);
        $filter->setTaskTypeId($taskTypeId);
        $filter->setIsCompleted($isCompleted);
        $filter->setEntityType($entityType);
        $filter->setEntityIds($entityIds);
        $filter->setOrder($orderBy, $orderDirection);

        return $filter;
    }

}