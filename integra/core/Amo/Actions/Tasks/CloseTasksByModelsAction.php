<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;

use Exception;
use Nikacrm\Core\Amo\Actions\Account\GetAccountAction;
use Nikacrm\Core\Container;


class CloseTasksByModelsAction extends AmoTasksAction

{


    protected function logic()
    {
        $tasksModels     = $this->params['models'] ?? [];
        $resultText      = $this->params['result_text'] ?? 'Задача закрыта программно скриптом';
        $tasksCollection = new TasksCollection();
        try {
            foreach ($tasksModels as $model) {
                $model->setIsCompleted(true);
                $model->setResult($resultText);
                $tasksCollection->add($model);
            }

            $result = $this->apiTasks->update($tasksCollection);

            //$result = [];

            return $result;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        } catch (Exception $e) {
        }
    }

}