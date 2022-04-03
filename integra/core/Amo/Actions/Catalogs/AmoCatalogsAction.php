<?php

namespace Nikacrm\Core\Amo\Actions\Catalogs;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoCatalogsAction extends AmoAction
{

    protected $apiCatalogs; //api объект библиотеки амо
    protected $catalogsService; //обертка api методов для каталогов

    public function __construct()
    {
        parent::__construct();

        $this->catalogsService = $this->apiClient->catalogsService();
        $this->apiCatalogs     = $this->catalogsService->api;
    }


}