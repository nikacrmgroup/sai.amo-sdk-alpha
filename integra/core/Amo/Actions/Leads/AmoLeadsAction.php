<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoLeadsAction extends AmoAction
{

    protected $apiLeads; //api объект библиотеки амо
    protected $leadsService; //обертка api методов для сделок

    public function __construct()
    {
        parent::__construct();

        $this->leadsService = $this->apiClient->leadsService();
        $this->apiLeads     = $this->leadsService->api;
    }


}