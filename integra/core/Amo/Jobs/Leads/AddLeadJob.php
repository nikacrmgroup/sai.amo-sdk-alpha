<?php

namespace Nikacrm\Core\Amo\Jobs\Leads;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;

use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;
use Exception;
use Nikacrm\Core\Amo\Actions\Contacts\PrepareContactModelAction;
use Nikacrm\Core\Amo\Actions\Leads\PrepareLeadModelAction;
use Nikacrm\Core\Amo\Base\AmoDTO;
use Nikacrm\Core\Amo\Base\AmoJob;
use Nikacrm\Core\Amo\DTO\ContactDTO;
use Nikacrm\Core\Amo\DTO\LeadDTO;

class AddLeadJob extends AmoJob
{

    protected $apiLeads; //api объект библиотеки амо
    protected $leadsService; //обертка api методов для сделок

    public function __construct()
    {
        parent::__construct();

        $this->leadsService = $this->apiClient->leadsService();
        $this->apiLeads     = $this->leadsService->api;
    }

    public function linkContacts(LeadModel $leadModel, $dto): void
    {
        /*Проверяем, есть ли контакты, которые нужно прикрепить*/
        $contactIds = $dto->getLinkedContactIds();
        if ($contactIds) {
            $contactsCollection = new ContactsCollection();
            foreach ($contactIds as $index => $contactId) {
                $contactModel = (new ContactModel())
                  ->setId($contactId);
                //если это первый контакт - будет главным
                if ($index === 0) {
                    $contactModel->setIsMain(true);
                }
                $contactsCollection
                  ->add(
                    $contactModel
                  );
            }
            $leadModel
              ->setContacts($contactsCollection);
        }
    }

    public function linkCompany(LeadModel $leadModel, $dto): void
    {
        /*Проверяем, есть ли компания, которую нужно прикрепить*/
        $companyId = $dto->getLinkedCompanyId();

        if ($companyId) {
            $leadModel->setCompany(
              (new CompanyModel())
                ->setId($companyId)
            );
        }
    }

    protected function prepareLead(LeadDTO $dto): LeadModel
    {
        /* @var LeadModel $leadModel */
        $leadModel = (new PrepareLeadModelAction())->exec(['dto' => $dto]);

        $this->linkContacts($leadModel, $dto);
        $this->linkCompany($leadModel, $dto);
        //$this->getLinkCatalogElements($leadModel, $dto);

        $this->logger->save('🌼 Подготовлена модель сделки: '.je(dismount($leadModel)), 'debug');

        return $leadModel;
    }

    private function validateDto()
    {
        try {
            if (!isset($this->params['dto'])) {
                throw new \RuntimeException('Нет dto');
            }
        } catch (Exception $e) {
            $this->logException($e);
            die();
        }
        try {
            if (!($this->params['dto'] instanceof AmoDTO)) {
                throw new \RuntimeException('Не то dto');
            }
        } catch (Exception $e) {
            $this->logException($e);
            die();
        }
    }

    protected function logic(): LeadsCollection
    {
        $this->validateDto();

        /* @var \Nikacrm\Core\Amo\DTO\LeadDTO $dto */
        $dto             = $this->params['dto'];
        $leadsCollection = new LeadsCollection();

        $lead = $this->prepareLead($dto);

        $leadsCollection->add($lead);

        //Создадим сделки
        try {
            /* @var LeadsCollection $addedLeadsCollection */
            $addedLeadsCollection = $this->apiLeads->add($leadsCollection);
            $addResponse          = $this->apiLeads->getLastRequestInfo();
            $this->logger->save('🟦 Ответ от амо такой: '.getmypid().' : '.je($addResponse));

            if ($addedLeadsCollection->count() > 0) {
                $links = $this->getLinkCatalogElements($lead, $dto);
                if ($links->count() > 0) {
                    $this->apiLeads->link($lead, $links);
                    $linkedResponse = $this->apiLeads->getLastRequestInfo();
                    $this->logger->save('🟦 Ответ прикрепления товаров от амо такой: '.getmypid().' : '.je($linkedResponse));
                }
            } else {
                $this->logger->save('❌ Ответ от амо пришел с пустой коллекцией: '.getmypid().' : '.je
                  (dismount($addedLeadsCollection)), 'error');
            }


            return $addedLeadsCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            die;
        }
    }

    private function getLinkCatalogElements(LeadModel $leadModel, LeadDTO $dto): LinksCollection
    {
        /*Проверяем, есть ли товары/элементы каталога*/
        $catalogElementsDtoArray   = $dto->getLinkedCatalogElementsDto();
        $catalogElementsCollection = $dto->getLinkedCatalogElementsCollection();

        $links = new LinksCollection();
        if ($catalogElementsCollection) {
            $leadModel->setCatalogElementsLinks($catalogElementsCollection);
            foreach ($catalogElementsCollection as $catalogElementModel) {
                //$links = new LinksCollection();
                $links->add($catalogElementModel);
            }
        }

        return $links;


        /*
         * $catalogElementsCollection = new CatalogElementsCollection();
            $catalogElementsCollection = $catalogElementsCollection->fromArray(
                $lead[AmoCRMApiRequest::EMBEDDED][self::CATALOG_ELEMENTS]
            );
            $leadModel->setCatalogElementsLinks($catalogElementsCollection);
         *
         * */
        //        if ($catalogElementsDtoArray) {
        //            $leadModel->setCompany(
        //              (new CompanyModel())
        //                ->setId($companyId)
        //            );
        //        }
    }

}