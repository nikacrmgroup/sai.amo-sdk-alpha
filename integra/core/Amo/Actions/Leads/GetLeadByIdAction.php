<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Filters\FilterLeadsByIds;


class GetLeadByIdAction extends AmoLeadsAction

{

    protected function logic()
    {
        //Получим сделки и следующую страницу сделок
        try {
            //Создадим фильтр по id сделки и ответственному пользователю
            $filter          = (new FilterLeadsByIds())->create($this->params);
            $leadsCollection = $this->apiLeads->get($filter);
            $leadsCollection = $this->leadsService->yieldRemainingCollection($leadsCollection);

            return $leadsCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            die;
        }
    }

}