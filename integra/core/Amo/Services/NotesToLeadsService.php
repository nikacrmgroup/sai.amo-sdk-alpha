<?php
/**
 * amoCRM API client Notes service
 */

namespace Nikacrm\Core\Amo\Services;

use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Base\AmoService;


class NotesToLeadsService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        $this->api = $this->apiClient->notes(EntityTypesInterface::LEADS);
    }


}