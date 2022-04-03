<?php
/**
 * amoCRM API client service групп кастом полей
 */

namespace Nikacrm\Core\Amo\Services;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\AmoService;


class CustomFieldsService extends AmoService
{

    public function __construct($id, $entityType = EntityTypesInterface::LEADS)
    {
        parent::__construct($id);
        $this->entityType = $entityType;
        $this->init($entityType);
    }


    public function init($entityType)
    {
        //Сервис групп полей
        try {
            $this->api = $this->apiClient->customFields($entityType);
        } catch (AmoCRMApiException $e) {
            print_error($e);
            die;
        }
    }


}