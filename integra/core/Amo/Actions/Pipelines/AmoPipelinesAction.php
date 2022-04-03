<?php

namespace Nikacrm\Core\Amo\Actions\Pipelines;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoPipelinesAction extends AmoAction
{

    //TODO сделать кеширование
    protected $api; //api объект библиотеки амо
    protected $pipelinesService; //обертка api методов для воронок

    public function __construct()
    {
        parent::__construct();

        $this->pipelinesService = $this->apiClient->pipelinesService();
        $this->api              = $this->pipelinesService->api;
    }


}