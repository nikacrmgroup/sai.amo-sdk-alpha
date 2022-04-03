<?php

namespace Nikacrm\Core\Amo\Actions\CustomFields;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Container;


class GetCustomFieldsByEntityAction extends AmoCustomFieldsAction

{

    protected const DEFAULT_TTL = 2 * 3600; //2 hours

    private $entityType;

    public function __construct($entityType = EntityTypesInterface::LEADS)
    {
        parent::__construct($entityType);
        $this->entityType = $entityType;
    }

    public function fetchData()
    {
        $this->customFieldsService->init($this->entityType);

        $firstCustomFieldsCollection = $this->customFieldsService->api->get();

        return $this->customFieldsService->yieldRemainingCollection
        ($firstCustomFieldsCollection);
    }


    protected function logic()
    {
        try {
            /* @var \Nikacrm\Core\Cache $cache */
            $cache = Container::get('cache');

            return $cache->getCachedData("custom_fields_{$this->entityType}", [$this, 'fetchData'], self::DEFAULT_TTL);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}