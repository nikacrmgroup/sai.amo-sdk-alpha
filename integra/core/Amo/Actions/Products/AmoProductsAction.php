<?php

namespace Nikacrm\Core\Amo\Actions\Products;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoProductsAction extends AmoAction
{

    protected $apiProducts; //api объект библиотеки амо
    protected $productsService; //обертка api методов для товаров

    public function __construct()
    {
        parent::__construct();

        $this->productsService = $this->apiClient->productsService();
        $this->apiProducts     = $this->productsService->api;
    }


}