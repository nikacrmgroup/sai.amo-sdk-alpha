<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Filters\LeadsFilter;


class GetAllLeadsWithFilterAndCatalogsAction extends AmoLeadsAction

{

    private LeadsFilter $filter;

    public function __construct(LeadsFilter $filter)
    {
        parent::__construct();
        $this->filter = $filter;
    }


    protected function logic()
    {
        //Получим сделки и следующую страницу сделок
        try {
            $with            = ['catalog_elements'];
            $leadsCollection = $this->apiLeads->get($this->filter, $with);
            $leadsCollection = $this->leadsService->yieldRemainingCollection($leadsCollection);

            return $leadsCollection;
        } catch (AmoCRMApiException $e) {
            //TODO
            $this->logException($e);
            //die;
        }
    }

}