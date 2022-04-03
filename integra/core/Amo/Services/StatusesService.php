<?php
/**
 * amoCRM API client Catalogs service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;


class StatusesService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
    }

    public function get(int $pipelineId)
    {
        $this->api = $this->apiClient->statuses($pipelineId);

        return $this->api;
    }


}