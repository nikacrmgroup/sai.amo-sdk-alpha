<?php

namespace Nikacrm\Core\Amo\Actions\Companies;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use Nikacrm\Core\Amo\Filters\FilterCompaniesByIds;
use Nikacrm\Core\Amo\Filters\FilterContactsByIdsOrderCreatedByDesc;


class GetCompaniesByIdWithLeadsAction extends AmoCompaniesAction

{

    protected function logic()
    {
        try {
            $with                = [ContactModel::LEADS];
            $filter              = (new FilterCompaniesByIds())->create($this->params);
            $companiesCollection = $this->apiCompanies->get($filter, $with);
            $companiesCollection = $this->companiesService->yieldRemainingCollection($companiesCollection);

            return $companiesCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}