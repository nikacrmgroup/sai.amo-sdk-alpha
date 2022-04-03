<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoTasksAction extends AmoAction
{

    protected $apiTasks; //api объект библиотеки амо
    protected $tasksService; //обертка api методов для каталогов

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->tasksService = $this->apiClient->tasksService();
        $this->apiTasks     = $this->tasksService->api;
    }


}