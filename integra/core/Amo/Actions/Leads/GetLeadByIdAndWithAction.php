<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Filters\FilterLeadsByIds;


class GetLeadByIdAndWithAction extends AmoLeadsAction

{


    protected function logic()
    {
        //Получим сделки и следующую страницу сделок
        try {
            $with   = $this->params['with'] ?? ['contacts', 'catalog_elements'];
            $filter = (new FilterLeadsByIds())->create($this->params);

            $leadsCollection = $this->apiLeads->get($filter, $with);
            $leadsCollection = $this->leadsService->yieldRemainingCollection($leadsCollection);

            return $leadsCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}