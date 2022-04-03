<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\LeadModel;
use Nikacrm\Core\Amo\Actions\CustomFields\PrepareCustomFieldsCollectionAction;
use Nikacrm\Core\Amo\Actions\Tags\PrepareTagsCollectionAction;


class PrepareLeadModelAction extends AmoLeadsAction

{

    protected function logic(): LeadModel
    {
        /***/
        $dto = $this->params['dto'] ?? false;
        //todo test instance
        try {
            if (!$dto) {
                throw new AmoCRMApiException('Пустой dto при создании модели лида');
            }
            /* @var \Nikacrm\Core\Amo\DTO\LeadComplexDTO $dto */


            $leadModel = (new LeadModel())
              ->setName($dto->getLeadName())
              ->setPrice($dto->getPrice())
              ->setPipelineId($dto->getPipelineId())
              ->setStatusId($dto->getStatusId());

            $leadModel->setTags($this->prepareTags($dto->getTags()));
            $customFields = $dto->getCustomFields() ?? [];
            if (!empty($customFields)) {
                $customFieldsCollection = (new PrepareCustomFieldsCollectionAction())->exec(['custom_fields' => $customFields]);
                $leadModel
                  ->setCustomFieldsValues(
                    $customFieldsCollection
                  );
            }


            return $leadModel;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

    protected function prepareTags(array $tags = [])
    {
        $tagsCollection = (new PrepareTagsCollectionAction())->exec(['tags' => $tags]);

        return $tagsCollection;
    }
}