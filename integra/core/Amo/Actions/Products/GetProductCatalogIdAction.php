<?php

namespace Nikacrm\Core\Amo\Actions\Products;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Cache;
use Nikacrm\Core\Container;


class GetProductCatalogIdAction extends AmoProductsAction

{

    protected const DEFAULT_TTL = 30 * 86400; //30 days


    public function fetchData()
    {
        $catalogCollection = $this->productsService->get();
        $productsCatalog   = $catalogCollection->getBy('name', 'Товары');

        return $productsCatalog->getId();
    }

    protected function logic()
    {
        try {
            /* @var \Nikacrm\Core\Cache $cache */
            $cache = Container::get('cache');

            return $cache->getCachedData('products_catalog_id', [$this, 'fetchData'], self::DEFAULT_TTL);
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }

}