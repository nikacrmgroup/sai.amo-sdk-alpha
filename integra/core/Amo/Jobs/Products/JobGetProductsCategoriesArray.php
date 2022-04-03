<?php

namespace Nikacrm\Core\Amo\Jobs\Products;

use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Actions\CustomFields\GetCustomFieldsByEntityAction;
use Nikacrm\Core\Amo\Actions\Products\GetProductCatalogIdAction;
use Nikacrm\Core\Amo\Base\AmoJob;

class JobGetProductsCategoriesArray extends AmoJob
{

    protected function logic(): array
    {
        $productCatalogId       = (new GetProductCatalogIdAction())->exec();
        $customFieldsCollection = (new GetCustomFieldsByEntityAction(EntityTypesInterface::CATALOGS
          .':'.
          $productCatalogId))->exec();
        $categoryCollection     = $customFieldsCollection->getBy('type', 'category');

        return $categoryCollection->toArray();
    }


}