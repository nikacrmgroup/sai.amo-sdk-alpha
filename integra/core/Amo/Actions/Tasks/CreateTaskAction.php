<?php
/** @noinspection ALL */

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Collections\TasksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\TaskModel;
use Exception;
use Nikacrm\Core\Amo\DTO\TaskDTO;
use Nikacrm\Core\Container;


class CreateTaskAction extends AmoTasksAction

{

    public function __construct(TaskDTO $taskDTO)
    {
        parent::__construct($taskDTO);
        $this->dto = $taskDTO;
    }


    protected function logic()
    {
        //Создадим задачу
        $tasksCollection = new TasksCollection();
        $task            = new TaskModel();
        try {
            $params = $this->dto->getParams();
            $task->setTaskTypeId($params['type_id'])
                 ->setText($params['text'])
                 ->setCompleteTill($params['complete_till'])
                 ->setEntityType($params['entity_type'])
                 ->setEntityId($params['entity_id'])
                 ->setDuration($params['duration'])
                 ->setResponsibleUserId($params['responsible_user_id']);
            $tasksCollection->add($task);

            try {
                $tasksCollection = $this->apiTasks->add($tasksCollection);
                Container::get('app_logger')->save('🟢 Задача '.json_encode($tasksCollection,
                    JSON_UNESCAPED_UNICODE).' добавлена 💚 по таким параметрам: '.json_encode
                  ($task->toArray(),
                    JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                  'info');
            } catch (AmoCRMApiException $e) {
                $this->logException($e);
            }
        } catch (Exception $e) {
            //TODO
            $this->logException($e);
        }
    }

}