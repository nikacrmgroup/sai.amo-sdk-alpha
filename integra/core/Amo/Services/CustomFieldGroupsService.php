<?php
/**
 * amoCRM API client service групп кастом полей (табы в карточке)
 */

namespace Nikacrm\Core\Amo\Services;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\AmoService;


class CustomFieldGroupsService extends AmoService
{

    public function __construct($id, $entityType = EntityTypesInterface::LEADS)
    {
        parent::__construct($id);
        $this->entityType = $entityType;
        $this->initApi($entityType);
    }

    public function get($entityType)
    {
        //Сервис групп полей
        try {
            return $this->apiClient->customFieldGroups($entityType);
        } catch (AmoCRMApiException $e) {
            print_error($e);
            die;
        }
    }

    protected function initApi($entityType)
    {
        //Сервис групп полей
        try {
            $this->api = $this->apiClient->customFieldGroups($entityType);
        } catch (AmoCRMApiException $e) {
            print_error($e);
            die;
        }
    }


}