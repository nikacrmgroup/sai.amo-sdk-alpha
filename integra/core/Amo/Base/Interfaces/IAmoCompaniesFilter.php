<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\CompaniesFilter;

interface IAmoCompaniesFilter
{

    public function create(): CompaniesFilter;


}