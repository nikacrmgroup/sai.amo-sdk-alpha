<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\EntitiesLinksFilter;
use Nikacrm\Core\Amo\Base\Interfaces\IEntitiesLinksFilter;

class FilterIEntitiesLinks implements IEntitiesLinksFilter
{

    public function create($params = ['ids' => []]): EntitiesLinksFilter
    {
        $ids = $params['ids'];

        $filter = new EntitiesLinksFilter($ids);

        return $filter;
    }

}