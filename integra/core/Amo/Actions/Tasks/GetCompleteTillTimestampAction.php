<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Exceptions\AmoCRMApiException;
use DateTime;
use DateTimeZone;
use Exception;
use Nikacrm\Core\Amo\Actions\Account\GetAccountAction;
use Nikacrm\Core\Container;


class GetCompleteTillTimestampAction extends AmoTasksAction

{


    protected function logic()
    {
        /*Если params пустой, то ставим время Сегодня*/
        $completeTill = $this->params['complete_till'] ?? 'today 23:59';

        try {
            $account      = (new GetAccountAction())->exec();
            $timeSettings = $account['datetime_settings']->toArray();
            $date         = new DateTime($completeTill,
              new DateTimeZone($timeSettings['timezone']));
            $timestamp    = $date->getTimestamp();
            Container::get('app_logger')->save('🟢 Сформирована дата complete_till: '.je($timestamp),
              'info');

            return $date->getTimestamp();
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        } catch (Exception $e) {
        }
    }

}