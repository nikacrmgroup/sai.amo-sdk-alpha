<?php

namespace Nikacrm\Core\Amo\Jobs\Notes;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Models\NoteType\CommonNote;
use Exception;
use Nikacrm\Core\Amo\Actions\Notes\CreateNotesToLeadModelsAction;
use Nikacrm\Core\Amo\Base\AmoJob;

class CreateCommonNoteForLeadCollectionJob extends AmoJob
{

    protected function logic()
    {
        try {
            if (!isset($this->params['leads'])) {
                throw new \RuntimeException('Нет leads');
            }
        } catch (Exception $e) {
            $this->logException($e);
            die();
        }
        try {
            if (!($this->params['leads'] instanceof LeadsCollection)) {
                throw new \RuntimeException('Не тот формат, нужны LeadsCollection');
            }
        } catch (Exception $e) {
            $this->logException($e);
            die();
        }
        $leadCollection = $this->params['leads'];
        $text           = $this->params['text'] ?? 'Тестовое примечание к сделке';
        //$lead        = $leadCollection->first();
        $notesCreate = [];
        foreach ($leadCollection as $lead) {
            if ($lead) {
                //TODO фабрику примечаний
                $noteModel = new CommonNote();
                $noteModel->setCreatedBy(0)
                          ->setEntityId($lead->getId())
                          ->setText($text);
                $notesCreate[] = $noteModel;
            }
        }

        (new CreateNotesToLeadModelsAction())->exec(['models' => $notesCreate]);
    }
}