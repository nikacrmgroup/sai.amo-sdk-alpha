<?php
/**
 * amoCRM API client Catalogs service
 */

namespace Nikacrm\Core\Amo\Services;

use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\AmoService;


class LinksService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
    }

    public function create(string $entityType = EntityTypesInterface::LEADS)
    {
        $this->api = $this->apiClient->links($entityType);

        return $this->api;
    }


}