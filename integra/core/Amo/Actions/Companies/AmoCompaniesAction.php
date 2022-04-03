<?php

namespace Nikacrm\Core\Amo\Actions\Companies;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoCompaniesAction extends AmoAction
{

    protected $apiCompanies; //api объект библиотеки амо
    protected $companiesService; //обертка api методов для компаний

    public function __construct()
    {
        parent::__construct();

        $this->companiesService = $this->apiClient->companiesService();
        $this->apiCompanies     = $this->companiesService->api;
    }


}