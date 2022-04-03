<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Exceptions\AmoCRMApiException;
use Exception;
use Nikacrm\Core\Amo\Filters\FilterTasksByLeadId;
use Nikacrm\Core\Container;


class GetTaskByLeadIdAction extends AmoTasksAction

{

    protected function logic()
    {
        try {
            $filter = (new FilterTasksByLeadId())->create($this->params);

            return $this->apiTasks->get($filter);
        } catch (AmoCRMApiException $e) {
            //$this->logException($e);
            $errorCode  = $e->getErrorCode();
            $errorTitle = $e->getTitle();
            Container::get('app_logger')->save('💢 Амо вернула ответ, мол нет задач прикрепленных к сделке в виде exception;)'.$errorTitle.' | '.$errorCode,
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