<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\LeadsFilter;

interface IAmoLeadsFilter
{

    public function create(): LeadsFilter;


}