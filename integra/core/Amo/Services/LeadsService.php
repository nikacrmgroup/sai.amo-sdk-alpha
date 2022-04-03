<?php
/**
 * amoCRM API client Leads service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;


class LeadsService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        $this->api = $this->apiClient->leads();
    }

}