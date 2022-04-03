<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Actions\Tasks\AmoTasksAction;


class CreateTasksModelsAction extends AmoTasksAction

{

    protected function logic()
    {
        $tasksModels     = $this->params['models'] ?? [];
        $tasksCollection = new TasksCollection();
        try {
            foreach ($tasksModels as $model) {
                $tasksCollection->add($model);
            }

            //TODO Note!!!
            $result = $this->apiTasks->add($tasksCollection);

            //$result = [];

            return $result;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }
}