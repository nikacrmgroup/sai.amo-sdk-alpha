<?php

namespace Nikacrm\Core\Amo\Actions\CustomFieldGroups;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;


class GetCustomFieldGroupsByEntityAction extends AmoCustomFieldGroupsAction

{

    private $entityType;

    public function __construct($entityType = EntityTypesInterface::LEADS)
    {
        parent::__construct();
        $this->entityType = $entityType;
    }

    public function exec($params = [], ...$args)
    {
        $this->params = $params;
        $this->args   = $args;

        return $this->run([$this, 'logic']);
    }

    protected function logic()
    {
        try {
            $groupFieldGroupsService     = $this->customFieldGroupsService->get($this->entityType);
            $firstFieldGroupsCollection  = $groupFieldGroupsService->get();
            $customFieldGroupsCollection = $this->customFieldGroupsService->yieldRemainingCollection
            ($firstFieldGroupsCollection);

            return $customFieldGroupsCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}