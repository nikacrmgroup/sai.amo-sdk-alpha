<?php

namespace Nikacrm\Core\Amo\Actions\Links;

use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoLinksAction extends AmoAction
{

    protected $apiLinks; //api объект библиотеки амо
    protected $linksService; //обертка api методов для каталогов

    public function __construct()
    {
        parent::__construct();

        $this->linksService = $this->apiClient->linksService();
        $this->apiLinks     = $this->linksService->api;
    }


}