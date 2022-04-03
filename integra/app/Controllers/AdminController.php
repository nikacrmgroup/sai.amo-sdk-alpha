<?php

declare(strict_types=1);


namespace Nikacrm\App\Controllers;


use Nikacrm\App\Features\Stock\CRUDProductsFeature;
use Nikacrm\App\Features\Stock\DashboardFeature;
use Nikacrm\App\Features\Stock\Data\InitData;
use Nikacrm\App\Features\Stock\TransactionsFeature;
use Nikacrm\Core\Amo\ApiClient;
use Nikacrm\Core\Base\Controller;

class AdminController extends Controller
{


    public function index()
    {
        (new DashboardFeature())->adminIndex();
    }

    public function initProducts()
    {
        //checkAccess();
        $apiClient = ApiClient::boot();
        (new InitData())->initReserveProducts();
    }

    public function showProducts()
    {
        $apiClient = ApiClient::boot();
        (new DashboardFeature())->adminBuild();
    }

    public function showTransactions()
    {
        (new TransactionsFeature())->show();
    }


}