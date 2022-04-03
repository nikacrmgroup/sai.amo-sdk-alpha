<?php

namespace Nikacrm\Core\Amo\Actions\Notes;

use AmoCRM\Collections\NotesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;


class CreateNotesToContactModelsAction extends AmoNotesAction

{

    protected function logic()
    {
        $notesModels     = $this->params['models'] ?? [];
        $notesCollection = new NotesCollection();
        try {
            foreach ($notesModels as $model) {
                $notesCollection->add($model);
            }

            //TODO Note!!!
            $result = $this->apiNotesToContacts->add($notesCollection);

            $result = [];

            return $result;
        } catch (AmoCRMApiException $e) {
            $this->logException($e);
            //die;
        }
    }
}