<?php

namespace Nikacrm\Core\Amo\Actions\Pipelines;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Container;


class GetAllPipelinesAction extends AmoPipelinesAction

{

    protected const DEFAULT_TTL = 10 * 86400; //10 days


    public function fetchData()
    {
        try {
            return $this->pipelinesService->get();
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

            return $cache->getCachedData('all_pipelines', [$this, 'fetchData'], self::DEFAULT_TTL);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}