<?php

namespace Nikacrm\Core\Amo\Actions\CustomFieldGroups;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoCustomFieldGroupsAction extends AmoAction
{

    protected $apiCustomFieldGroups; //api объект библиотеки амо
    protected $customFieldGroupsService; //обертка api методов для групп кастом полей (табы в карточке)

    public function __construct()
    {
        parent::__construct();

        $this->customFieldGroupsService = $this->apiClient->customFieldGroupsService();
        $this->apiCustomFieldGroups     = $this->customFieldGroupsService->api;
    }


}