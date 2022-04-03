<?php

namespace Nikacrm\Core\Amo\Base\Interfaces;

use AmoCRM\Filters\TasksFilter;

interface IAmoTasksFilter
{

    public function create(): TasksFilter;


}