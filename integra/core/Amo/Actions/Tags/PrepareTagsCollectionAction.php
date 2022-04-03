<?php

namespace Nikacrm\Core\Amo\Actions\Tags;

use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\TagModel;


class PrepareTagsCollectionAction extends AmoTagsAction

{

    protected function logic()
    {
        $tags           = $this->params['tags'] ?? [];
        $tagsCollection = new TagsCollection();
        try {
            foreach ($tags as $tag) {
                $tagsCollection
                  ->add(
                    (new TagModel())
                      ->setName($tag)
                  );
            }

            return $tagsCollection;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }
}