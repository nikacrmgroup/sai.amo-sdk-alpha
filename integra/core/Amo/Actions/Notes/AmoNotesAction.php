<?php

namespace Nikacrm\Core\Amo\Actions\Notes;

use Nikacrm\Core\Amo\Base\AmoAction;

/**
 * @property $notesToLeadsService
 * @property $notesToContactsService
 * @property $notesToCompaniesService
 * @property $apiNotesToLeads
 * @property $apiNotesToContacts
 * @property $apiNotesToCompanies
 */
abstract class AmoNotesAction extends AmoAction
{


    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->notesToLeadsService     = $this->apiClient->notesToLeadsService();
        $this->notesToContactsService  = $this->apiClient->notesToContactsService();
        $this->notesToCompaniesService = $this->apiClient->notesToCompaniesService();

        $this->apiNotesToLeads     = $this->notesToLeadsService->api;
        $this->apiNotesToContacts  = $this->notesToContactsService->api;
        $this->apiNotesToCompanies = $this->notesToCompaniesService->api;
    }


}