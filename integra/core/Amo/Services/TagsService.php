<?php
/**
 * amoCRM API client Actions service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;


class TagsService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        //$this->api = $this->apiClient->tags();
    }


}