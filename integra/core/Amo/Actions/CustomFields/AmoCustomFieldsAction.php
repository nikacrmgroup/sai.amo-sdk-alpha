<?php

namespace Nikacrm\Core\Amo\Actions\CustomFields;

use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoCustomFieldsAction extends AmoAction
{

    protected $apiCustomFields; //api объект библиотеки амо
    protected $customFieldsService; //обертка api методов для групп кастом полей (табы в карточке)

    public function __construct($entityType = EntityTypesInterface::LEADS)
    {
        parent::__construct();

        $this->customFieldsService = $this->apiClient->customFieldsService($entityType);
        $this->apiCustomFields     = $this->customFieldsService->api;
    }


}