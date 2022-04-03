<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\TasksFilter;

interface AmoTaskFilterInterface
{

    public function create(): TasksFilter;


}