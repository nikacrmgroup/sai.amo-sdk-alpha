<?php

namespace Nikacrm\Core\Amo\Actions\Contacts;

use Nikacrm\Core\Amo\Base\AmoAction;

abstract class AmoContactsAction extends AmoAction
{

    protected $apiContacts; //api объект библиотеки амо
    protected $contactsService; //обертка api методов для контактов

    public function __construct()
    {
        parent::__construct();

        $this->contactsService = $this->apiClient->contactsService();
        $this->apiContacts     = $this->contactsService->api;
    }


}