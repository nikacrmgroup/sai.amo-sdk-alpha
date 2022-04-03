<?php

namespace Nikacrm\Core\Amo\Actions\Tasks;

use AmoCRM\Exceptions\AmoCRMApiException;
use DateTime;
use DateTimeZone;
use Exception;
use Nikacrm\Core\Amo\Actions\Account\GetAccountAction;
use Nikacrm\Core\Container;


class GetAccountBasedTimestampAction extends AmoTasksAction

{


    protected function logic()
    {
        /*Если params пустой, то ставим время Сегодня*/
        $timestamp = $this->params['time'] ?? '';

        try {
            $account      = (new GetAccountAction())->exec();
            $timeSettings = $account['datetime_settings']->toArray();
            $date         = new DateTime($timestamp,
              new DateTimeZone($timeSettings['timezone']));
            $timestamp    = $date->getTimestamp();
            Container::get('app_logger')->save('🟢 Сформирована дата с учетом тайм зоны аккаунта: '.je($timestamp),
              'info');

            return $date->getTimestamp();
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        } catch (Exception $e) {
        }
    }

}