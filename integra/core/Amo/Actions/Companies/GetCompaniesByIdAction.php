<?php

namespace Nikacrm\Core\Amo\Actions\Companies;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Filters\FilterCompaniesByIds;


class GetCompaniesByIdAction extends AmoCompaniesAction

{

    protected function logic()
    {
        try {
            $with                = [];
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