<?php

namespace Nikacrm\Core\Amo\Actions\Contacts;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use Nikacrm\Core\Amo\Actions\CustomFields\PrepareCustomFieldsCollectionAction;
use Nikacrm\Core\Amo\Actions\Tags\PrepareTagsCollectionAction;


class PrepareContactModelAction extends AmoContactsAction

{

    protected function logic()
    {
        /* @var \Nikacrm\Core\Amo\DTO\ContactDTO $dto */
        $dto = $this->params['dto'] ?? false;
        //todo test instance
        try {
            if (!$dto) {
                throw new AmoCRMApiException('Пустой dto при создании модели контакта');
            }

            $contactModel = (new ContactModel());

            $name = $dto->getName();
            if ($dto->getName()) {
                $contactModel->setName($dto->getName());
            } else {
                $contactModel
                  ->setFirstName($dto->getFirstName())
                  ->setLastName($dto->getLastName());
            }
            $customFields = $dto->getCustomFields() ?? [];
            if (!empty($customFields)) {
                $customFieldsCollection = (new PrepareCustomFieldsCollectionAction())->exec(['custom_fields' => $customFields]);
                $contactModel
                  ->setCustomFieldsValues(
                    $customFieldsCollection
                  );
            }

            $contactModel->setTags($this->prepareTags($dto->getTags()));


            return $contactModel;
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