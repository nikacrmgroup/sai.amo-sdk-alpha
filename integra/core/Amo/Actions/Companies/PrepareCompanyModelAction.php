<?php

namespace Nikacrm\Core\Amo\Actions\Companies;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CompanyModel;
use Nikacrm\Core\Amo\Actions\CustomFields\PrepareCustomFieldsCollectionAction;


class PrepareCompanyModelAction extends AmoCompaniesAction

{

    protected function logic()
    {
        /* @var \Nikacrm\Core\Amo\DTO\CompanyDTO $dto */
        $dto = $this->params['dto'] ?? false;
        //todo test instance
        try {
            if (!$dto) {
                throw new AmoCRMApiException('Пустой dto при создании модели компании');
            }
            $companyModel = (new CompanyModel())
              ->setName($dto->getName());

            $customFields = $dto->getCustomFields() ?? [];
            if (!empty($customFields)) {
                $customFieldsCollection = (new PrepareCustomFieldsCollectionAction())->exec(['custom_fields' => $customFields]);
                $companyModel
                  ->setCustomFieldsValues(
                    $customFieldsCollection
                  );
            }

            return $companyModel;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}