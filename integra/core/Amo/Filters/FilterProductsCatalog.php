<?php

namespace Nikacrm\Core\Amo\Filters;

use AmoCRM\AmoCRM\Filters\CatalogsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\Interfaces\AmoCatalogFilterInterface;

class FilterProductsCatalog implements AmoCatalogFilterInterface
{

    public function create(): CatalogsFilter
    {
        $filter = new CatalogsFilter();

        $filter->setType(EntityTypesInterface::PRODUCTS_CATALOG_TYPE_STRING);

        return $filter;
    }

}