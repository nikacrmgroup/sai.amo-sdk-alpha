<?php

namespace Nikacrm\Core\Amo\Jobs\Leads;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;

use AmoCRM\Models\LeadModel;
use AmoCRM\Models\Unsorted\FormsMetadata;


use Exception;
use Nikacrm\Core\Amo\Actions\Companies\PrepareCompanyModelAction;
use Nikacrm\Core\Amo\Actions\Contacts\PrepareContactModelAction;
use Nikacrm\Core\Amo\Actions\Leads\PrepareLeadModelAction;
use Nikacrm\Core\Amo\Actions\Tags\PrepareTagsCollectionAction;
use Nikacrm\Core\Amo\Base\AmoDTO;
use Nikacrm\Core\Amo\Base\AmoJob;
use Nikacrm\Core\Amo\DTO\CompanyDTO;
use Nikacrm\Core\Amo\DTO\ContactDTO;
use Nikacrm\Core\Amo\DTO\LeadComplexDTO;

class AddComplexLeadsJob extends AmoJob
{

    protected $apiLeads; //api объект библиотеки амо
    protected $leadsService; //обертка api методов для сделок

    public function __construct()
    {
        parent::__construct();

        $this->leadsService = $this->apiClient->leadsService();
        $this->apiLeads     = $this->leadsService->api;
    }


    protected function prepareContactsCollection(ContactDTO $dto): ContactsCollection
    {
        $contactModel = (new PrepareContactModelAction())->exec(['dto' => $dto]);
        $this->logger->save('🌼 Подготовлена модель контакта: '.je(dismount($contactModel)), 'debug');

        return (new ContactsCollection())
          ->add(
            $contactModel
          );
    }


    protected function prepareFormMetaData()
    {
        /*Если слать мета, то сделка будет падать в неразобранное!*/
        return (new FormsMetadata())
          ->setFormId('my_best_form')
          ->setFormName('Обратная связь')
          ->setFormPage('https://example.com/form')
          ->setFormSentAt(mktime(date('h'), date('i'), date('s'), date('m'), date('d'), date('Y')))
          ->setReferer('https://google.com/search')
          ->setIp('192.168.0.1');
    }

    protected function prepareCompany(CompanyDTO $dto)
    {
        return (new PrepareCompanyModelAction())->exec(['dto' => $dto]);
    }

    /**
     * @param  \Nikacrm\Core\Amo\DTO\LeadComplexDTO  $dto
     * @return \AmoCRM\Models\LeadModel
     */
    protected function prepareLeadComplex(LeadComplexDTO $dto): LeadModel
    {
        //todo dto
        /* @var LeadModel $leadModel */
        $leadModel = (new PrepareLeadModelAction())->exec(['dto' => $dto]);
        $leadModel
          ->setContacts($this->prepareContactsCollection($dto->getContactDto()));


        //Добавить, если нужно, компанию
        $companyDto = $dto->getCompanyDto();
        if ($companyDto) {
            $leadModel->setCompany($this->prepareCompany($companyDto));
        }
        $this->logger->save('🌼 Подготовлена модель сделки: '.je(dismount($leadModel)), 'debug');

        //->setCompany($this->prepareCompany($dto->getCompanyDto()));

        return $leadModel;
    }

    protected function logic()
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


        /* @var \Nikacrm\Core\Amo\DTO\LeadComplexDTO $dto */
        $dto             = $this->params['dto'];
        $leadsCollection = new LeadsCollection();

        $lead = $this->prepareLeadComplex($dto);
        // ->setRequestId($externalLead['external_id']);


        /*$lead->setMetadata(
          $this->prepareFormMetaData()
        );*/


        $leadsCollection->add($lead);
        //}

        //Создадим сделки
        try {
            $addedLeadsCollection = $this->apiLeads->addComplex($leadsCollection);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            die;
        }

        /** @var LeadModel $addedLead */
        foreach ($addedLeadsCollection as $addedLead) {
            //Пройдемся по добавленным сделкам и выведем результат
            $leadId    = $addedLead->getId();
            $contactId = $addedLead->getContacts()->first()->getId();
            //$companyId = $addedLead->getCompany()->getId();

            $externalRequestIds = $addedLead->getComplexRequestIds();
            foreach ($externalRequestIds as $requestId) {
                $action    = $addedLead->isMerged() ? 'обновлены' : 'созданы';
                $separator = PHP_SAPI === 'cli' ? PHP_EOL : '<br>';
                //echo "Для сущности с ID {$requestId} были {$action}: сделка ({$leadId}), контакт ({$contactId}),
                // компания ({$companyId})".$separator;
            }
        }
    }

}