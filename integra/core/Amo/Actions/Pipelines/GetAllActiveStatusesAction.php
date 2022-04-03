<?php

namespace Nikacrm\Core\Amo\Actions\Pipelines;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Container;


class GetAllActiveStatusesAction extends AmoPipelinesAction

{

    protected const DEFAULT_TTL = 10 * 86400; //10 days


    public function fetchData()
    {
        try {
            $pipelines      = (new GetAllPipelinesAction())->exec();
            $pipelinesArray = $pipelines->toArray();
            $activeStatuses = [];
            foreach ($pipelinesArray as $pipeline) {
                $statuses = $pipeline['statuses'];
                foreach ($statuses as $status) {
                    /*Если первый статус не редактируем, то это неразобранное. Также фильтруем 142 и 143*/
                    if ($status['is_editable'] && !in_array($status['id'], [142, 143], true)) {
                        $activeStatuses[$pipeline['id']]['active_statuses'] [] = $status['id'];
                    }
                    $activeStatuses[$pipeline['id']]['all_statuses'] [] = $status;
                }


                $stop = 'Stop';
            }

            return $activeStatuses;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

    protected function logic()
    {
        try {
            /* @var \Nikacrm\Core\Cache $cache */
            $cache = Container::get('cache');

            return $cache->getCachedData('all_active_statuses', [$this, 'fetchData'], self::DEFAULT_TTL);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}