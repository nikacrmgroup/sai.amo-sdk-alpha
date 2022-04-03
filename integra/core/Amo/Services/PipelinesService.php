<?php
/**
 * amoCRM API client Catalogs service
 */

namespace Nikacrm\Core\Amo\Services;

use AmoCRM\Collections\Leads\Pipelines\PipelinesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Amo\Base\AmoService;


class PipelinesService extends AmoService
{

    public function __construct($id)
    {
        parent::__construct($id);
        $this->api = $this->apiClient->pipelines();
    }

    public function get(): PipelinesCollection
    {
        //Получим воронки
        try {
            return $this->api->get();
        } catch (AmoCRMApiException $e) {
            print_error($e);
            die;
        }
    }


}