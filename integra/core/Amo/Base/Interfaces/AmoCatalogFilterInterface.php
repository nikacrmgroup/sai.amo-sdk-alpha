<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\AmoCRM\Filters\CatalogsFilter;

interface AmoCatalogFilterInterface
{

    public function create(): CatalogsFilter;


}