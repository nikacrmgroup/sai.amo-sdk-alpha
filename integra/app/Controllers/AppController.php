<?php

declare(strict_types=1);


namespace Nikacrm\App\Controllers;

use Nikacrm\App\Features\Transactions\TransactionsFeature;
use Nikacrm\Core\Base\Controller;

class AppController extends Controller
{

    public function showTransactions()
    {
        (new TransactionsFeature())->show();
    }


}