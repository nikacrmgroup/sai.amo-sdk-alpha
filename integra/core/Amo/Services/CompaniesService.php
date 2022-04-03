<?php
/**
 * amoCRM API client Companies service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;


class CompaniesService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        $this->api = $this->apiClient->companies();
    }

}