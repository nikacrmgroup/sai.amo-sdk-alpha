<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Exceptions\AmoCRMApiException;
use DateTime;
use DateTimeZone;
use Exception;
use Nikacrm\Core\Amo\Actions\Account\GetAccountAction;


class GetTaskByLeadId extends AmoTasksAction

{


    protected function logic()
    {
        try {
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        } catch (Exception $e) {
        }
    }

}