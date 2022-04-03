<?php

namespace Nikacrm\Core\Amo\Base\Traits;

use AmoCRM\Exceptions\AmoCRMApiException;
use Generator;

trait TraitYieldRemainingCollection
{

    public function yieldRemainingCollection($initCollection, $api)
    {
        $collectionIterator = $this->collectionPageIterator($initCollection, $api);
        foreach ($collectionIterator as $collection) {
            foreach ($collection as $item) {
                $initCollection->add($item);
            }
        }

        return $initCollection;
    }

    private function collectionPageIterator($initCollection, $api): Generator
    {
        try {
            yield $productsCollectionPage = $api->nextPage($initCollection);
            while ($productsCollectionPage) {
                yield $productsCollectionPage = $api->nextPage($productsCollectionPage);
            }
        } catch (AmoCRMApiException $e) {
            //TODO
            $stop = 'Stop';
        }
    }

}