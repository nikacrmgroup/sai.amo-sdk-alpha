<?php

namespace Nikacrm\Core\Amo\Actions\Account;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\AccountModel;
use Nikacrm\Core\Amo\Filters\FilterAllContactsOrderCreatedByDesc;
use Nikacrm\Core\Amo\Filters\FilterAllContactsOrderUpdatedByDesc;
use Nikacrm\Core\Container;


class GetAccountAction extends AmoAccountAction

{

    protected const DEFAULT_TTL = 60 * 60; //60 минут
    protected array $params;

    public function fetchData()
    {
        $accountCollection = $this->apiAccount->getCurrent(AccountModel::getAvailableWith());

        //return $accountCollection;
        return $accountCollection->toArray();
    }

    protected function logic()
    {
        try {
            /* @var \Nikacrm\Core\Cache $cache */
            $cache = Container::get('cache');

            return $cache->getCachedData('account', [$this, 'fetchData'], self::DEFAULT_TTL);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

    public function exec($params = [], ...$args): array
    {
        return parent::exec($params, $args);
    }

}