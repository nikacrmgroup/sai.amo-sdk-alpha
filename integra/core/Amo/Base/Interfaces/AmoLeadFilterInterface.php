<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\LeadsFilter;

interface AmoLeadFilterInterface
{

    public function create(): LeadsFilter;


}