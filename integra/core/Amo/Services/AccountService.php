<?php
/**
 * amoCRM API client Catalogs service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;


class AccountService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        $this->api = $this->apiClient->account();
    }

    public function get()
    {
        return $this->api;
    }


}