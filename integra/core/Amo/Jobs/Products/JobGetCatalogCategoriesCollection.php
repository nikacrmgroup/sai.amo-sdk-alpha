<?php

namespace Nikacrm\Core\Amo\Jobs\Products;

use AmoCRM\Helpers\EntityTypesInterface;
use Nikacrm\Core\Amo\Actions\CustomFields\GetCustomFieldsByEntityAction;
use Nikacrm\Core\Amo\Base\AmoJob;

class JobGetCatalogCategoriesCollection extends AmoJob
{

    protected function logic()
    {
        $catalogId              = $this->params['catalog_id'] ?? 0;
        $customFieldsCollection = (new GetCustomFieldsByEntityAction(EntityTypesInterface::CATALOGS
          .':'.
          $catalogId))->exec();
        $categoryCollection     = $customFieldsCollection->getBy('type', 'category');

        return $categoryCollection;
    }


}