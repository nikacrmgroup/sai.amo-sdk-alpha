<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\AmoCRM\Filters\CatalogsFilter;

interface IAmoCatalogsFilter
{

    public function create(): CatalogsFilter;


}