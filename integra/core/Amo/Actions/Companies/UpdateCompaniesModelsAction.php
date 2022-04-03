<?php

namespace Nikacrm\Core\Amo\Actions\Companies;

use AmoCRM\Collections\CompaniesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;


class UpdateCompaniesModelsAction extends AmoCompaniesAction

{

    protected function logic()
    {
        $companiesModels     = $this->params['models'] ?? [];
        $companiesCollection = new CompaniesCollection();
        try {
            foreach ($companiesModels as $model) {
                $companiesCollection->add($model);
            }

            //TODO Note!!!
            $result = $this->apiCompanies->update($companiesCollection);

            //$result = [];

            return $result;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }
}