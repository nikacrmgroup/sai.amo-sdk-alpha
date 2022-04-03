<?php
/**
 * amoCRM API client  service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;


class ContactsService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        $this->api = $this->apiClient->contacts();
    }

}