<?php

namespace Nikacrm\Core\Amo\Actions\Leads;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Actions\Links\AmoLinksAction;
use Nikacrm\Core\Amo\Filters\FilterIEntitiesLinks;


class GetLeadsLinkedEntities extends AmoLinksAction

{

    protected function logic()
    {
        try {
            $filter   = (new FilterIEntitiesLinks())->create($this->params);
            $allLinks = $this->linksService->create(EntityTypesInterface::LEADS)->get($filter);

            return $allLinks;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}