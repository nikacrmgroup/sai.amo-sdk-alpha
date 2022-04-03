<?php

namespace Nikacrm\App\Features\Transactions;

interface ITransactions
{

    public const WEBHOOK_TYPE = 0;
    public const APP_TYPE     = 1;
    public const INIT_TYPE    = 2;
    public const USER_TYPE    = 3;
    public const ADMIN_TYPE   = 4;
    public const OTHER_TYPE   = 5;




}