<?php

namespace Nikacrm\Core\Amo\Actions\Account;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoAccountAction extends AmoAction
{

    protected $accountService; //api объект библиотеки амо
    protected $apiAccount; //обертка api методов для каталогов

    public function __construct()
    {
        parent::__construct();

        $this->accountService = $this->apiClient->accountService();
        $this->apiAccount     = $this->accountService->api;
    }


}