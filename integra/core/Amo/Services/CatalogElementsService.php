<?php
/**
 * amoCRM API client Catalog Elements service
 */

namespace Nikacrm\Core\Amo\Services;

use AmoCRM\Exceptions\AmoCRMApiException;
use Generator;
use Nikacrm\Core\Amo\Base\Service;


class CatalogElementsService extends Service
{

    public $api;

    public function __construct($id, $args = [])
    {
        parent::__construct($id);
        if (!isset($args['catalog_id'])) {
            throw new \Exception('Catalog id is empty!');
        }
        $this->api = $this->apiClient->catalogElements($args['catalog_id']);
    }

    public function get($filter = null)
    {
        try {
            return $this->api->get($filter);
        } catch (AmoCRMApiException $e) {
            //TODO
            print_error($e);
            die;
        }
    }

    public function yieldRemainingCollection($initCollection)
    {
        $collectionIterator = $this->collectionPageIterator($initCollection);
        foreach ($collectionIterator as $collection) {
            foreach ($collection as $item) {
                $initCollection->add($item);
            }
        }

        return $initCollection;
    }

    private function collectionPageIterator($initCollection): Generator
    {
        try {
            yield $productsCollectionPage = $this->api->nextPage($initCollection);
            while ($productsCollectionPage) {
                yield $productsCollectionPage = $this->api->nextPage($productsCollectionPage);
            }
        } catch (AmoCRMApiException $e) {
            //TODO
            $stop = 'Stop';
        }
    }


}