<?php

declare(strict_types=1);


namespace Nikacrm\App\Controllers;


use Nikacrm\App\Features\Api\CacheOperations\CacheOperationsFeature;
use Nikacrm\App\Features\Api\Leads\LeadCreateFeature;
use Nikacrm\App\Features\Api\ShopPartner\ShopPartnerOrderFeature;
use Nikacrm\App\Features\Stock\CRUDProductsFeature;
use Nikacrm\App\Features\Stock\TransactionsFeature;
use Nikacrm\Core\Amo\Integration\Auth;
use Nikacrm\Core\Base\Controller;
use Nikacrm\Core\Container;


class ApiController extends Controller
{

    public function auth()
    {
        Auth::run();
    }

    public function authSessionClear()
    {
        //check_access();
        /* @var \Nikacrm\Core\Session $session */
        $session = Container::get('session');
        $session->clearAllAuthSession();

        view('system/200', ['message' => 'Сессии авторизации пользователей сброшены']);
    }

    public function cacheClear()
    {
        //check_access();
        (Container::get('cache'))->clear();

        view('system/204');
    }

    public function getRenderedTransactionsJSON()
    {
        echo je((new TransactionsFeature())->render());
    }

    public function getTransactionsJSON()
    {
        $json = je((new TransactionsFeature())->params());
        header('Content-Type: application/json; charset=utf-8');
        echo $json;
    }

    public function orderCreate()
    {
        (new ShopPartnerOrderFeature())->create();
    }

    public function sessionClear()
    {
        check_access();
        /* @var \Nikacrm\Core\Session $session */
        $session = Container::get('session');
        $session->clearAllSession();

        view('system/200', ['message' => 'Сессии (все) сброшены']);
    }

    public function updateConfigsCache()
    {
        (Container::get('config'))->updateConfigsCache();
        view('system/200', ['message' => 'Кеш конфигов обновлен']);
    }

    public function updateContactsCache()
    {
        (new CacheOperationsFeature())->updateContactsCache();
    }


}