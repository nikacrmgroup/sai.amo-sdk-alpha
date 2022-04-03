<?php
/**
 * amoCRM API client Products service
 */

namespace Nikacrm\Core\Amo\Services;

use Nikacrm\Core\Amo\Base\AmoService;
use Nikacrm\Core\Amo\Filters\FilterProductsCatalogs;


class ProductsService extends AmoService
{

    private $apiProducts;
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
        parent::__construct($id);
        $this->apiProducts = $this->apiClient->products(); //в нем почти ничего нет
        $this->api         = $this->apiClient->catalogs();
    }

    public function get()
    {
        $filter = (new FilterProductsCatalogs())->create();

        return $this->apiClient->catalogs()->get($filter);
    }


}