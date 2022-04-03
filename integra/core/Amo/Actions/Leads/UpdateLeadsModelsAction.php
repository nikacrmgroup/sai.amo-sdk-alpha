<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;


class UpdateLeadsModelsAction extends AmoLeadsAction

{

    protected function logic()
    {
        $leadsModels     = $this->params['models'] ?? [];
        $leadsCollection = new LeadsCollection();
        try {
            foreach ($leadsModels as $model) {
                $leadsCollection->add($model);
            }

            //TODO Note!!!
            $result = $this->apiLeads->update($leadsCollection);

            //$result = [];

            return $result;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }
}