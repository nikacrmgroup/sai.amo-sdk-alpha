<?php

declare(strict_types=1);

namespace Nikacrm\App\Features\Api\CacheOperations;

use Exception;
use Nikacrm\Core\Amo\Actions\Contacts\GetAllContactsAction;
use Nikacrm\Core\Amo\ApiClient;
use Nikacrm\Core\Base\Feature;
use Nikacrm\Core\Base\Traits\TLogException;
use Nikacrm\Core\Container;


class CacheOperationsFeature extends Feature
{

    use TLogException;

    protected $config;
    protected $logger;

    /**
     * Удаляем и обновляем кеш контактов по запросу
     * @return void
     */
    public function updateContactsCache(): void
    {
        try {
            $startTime = microtime(true);

            $this->deleteItem('all_contacts');

            (new GetAllContactsAction())->exec();

            $totalTime  = 'За время:  '.number_format((microtime(true) - $startTime), 4).' секунд';
            $logMessage = '🟢 Кеш Контактов сброшен. '.$totalTime;
            $this->logger->save($logMessage, 'info');
        } catch (Exception $e) {
            $this->logException($e);
        }
    }

    public function deleteItem(string $cacheKey): void
    {
        /* @var \Nikacrm\Core\Cache $cache */
        $cache        = Container::get('cache');
        $cacheKeyUUID = $cache->prepareCacheName($cacheKey);
        $isDeleted    = $cache->deleteItem($cacheKeyUUID);
        if ($isDeleted) {
            $logMessage = "🟢 Кеш $cacheKey сброшен";
            $this->logger->save($logMessage, 'info');
        } else {
            $logMessage = "⭕ Кеш $cacheKey не сброшен. Вероятно его нет.";
            $this->logger->save($logMessage, 'error');
        }
    }


}