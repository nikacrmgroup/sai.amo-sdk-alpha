<?php

namespace Nikacrm\Core\Amo\Actions\Products;

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Container;


class GetAllProductsAction extends AmoProductsAction

{

    protected const DEFAULT_TTL = 2 * 3600; //2 hours

    public function fetchData()
    {
        $catalogCollection = $this->productsService->get();
        $productsCatalog   = $catalogCollection->getBy('name', 'Товары');
        $productsCatalogId = $productsCatalog->getId();

        $args = [
          'catalog_id' => $productsCatalogId,
        ];

        $catalogElementsService = $this->apiClient->catalogElementsService($args);

        return $catalogElementsService->get();
    }


    protected function logic()
    {
        try {
            /* @var \Nikacrm\Core\Cache $cache */
            $cache = Container::get('cache');

            return $cache->getCachedData("all_products", [$this, 'fetchData'], self::DEFAULT_TTL);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}