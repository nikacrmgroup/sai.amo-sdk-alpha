<?php

namespace Nikacrm\Core\Amo\Base;

use AmoCRM\Exceptions\AmoCRMApiException;
use Generator;

abstract class AmoService extends Service
{

    protected $api;

    public function yieldRemainingCollection($initCollection)
    {
        $collectionIterator = $this->collectionPageIterator($initCollection);
        foreach ($collectionIterator as $collection) {
            foreach ($collection as $lead) {
                $initCollection->add($lead);
            }
        }

        return $initCollection;
    }

    private function collectionPageIterator($initCollection): Generator
    {
        try {
            yield $leadsCollectionPage = $this->api->nextPage($initCollection);
            while ($leadsCollectionPage) {
                yield $leadsCollectionPage = $this->api->nextPage($leadsCollectionPage);
            }
        } catch (AmoCRMApiException $e) {
            $stop = 'Stop';
        }
    }

    //abstract protected function initApi();

}