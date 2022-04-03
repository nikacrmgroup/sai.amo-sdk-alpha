<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\TasksFilter;
use Exception;
use Nikacrm\Core\Amo\Filters\FilterTasksByLeadId;
use Nikacrm\Core\Container;


class GetTasksByFilterAction extends AmoTasksAction

{

    /**
     * @var \AmoCRM\Filters\TasksFilter
     */
    private TasksFilter $filter;

    public function __construct(TasksFilter $filter)
    {
        parent::__construct();
        $this->filter = $filter;
    }

    protected function logic()
    {
        try {
            return $this->apiTasks->get($this->filter);
        } catch (AmoCRMApiException $e) {
            //$this->logException($e);
            $errorCode  = $e->getErrorCode();
            $errorTitle = $e->getTitle();

            Container::get('app_logger')->save('💢 Амо вернула ответ, мол нет задач по фильтру в виде exception;)'
              .$errorTitle
              .' | '.$errorCode.'. Сам фильтр: '.je(dismount($this->filter)),
              'debug');
            if ($errorCode === 204 && $errorTitle === 'No content') {
                return false;
            }
            die;
        } catch (Exception $e) {
            $this->logException($e);
        }
    }

}