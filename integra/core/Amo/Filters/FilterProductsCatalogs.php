<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\AmoCRM\Filters\CatalogsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\Interfaces\IAmoCatalogsFilter;

class FilterProductsCatalogs implements IAmoCatalogsFilter
{

    public function create(): CatalogsFilter
    {
        $filter = new CatalogsFilter();

        $filter->setType(EntityTypesInterface::PRODUCTS_CATALOG_TYPE_STRING);

        return $filter;
    }

}