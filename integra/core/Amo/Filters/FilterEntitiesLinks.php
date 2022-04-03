<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\Filters\EntitiesLinksFilter;
use Nikacrm\Core\Amo\Base\Interfaces\EntitiesLinksFilterInterface;

class FilterEntitiesLinks implements EntitiesLinksFilterInterface
{

    public function create($params = ['ids' => []]): EntitiesLinksFilter
    {
        $ids = $params['ids'];

        $filter = new EntitiesLinksFilter($ids);

        return $filter;
    }

}