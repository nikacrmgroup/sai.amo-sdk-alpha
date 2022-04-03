<?php
/** @noinspection ALL */

namespace Nikacrm\Core\Amo\Actions\Notes;

use AmoCRM\Collections\NotesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use Exception;
use Nikacrm\Core\Amo\Actions\Notes\AmoNotesAction;
use Nikacrm\Core\Amo\DTO\Notes\ServiceNoteDTO;
use Nikacrm\Core\Container;


class CreateServiceNoteAction extends AmoNotesAction

{

    public function __construct(ServiceNoteDTO $noteDTO)
    {
        parent::__construct($noteDTO);
        $this->dto = $noteDTO;
    }


    protected function logic()
    {
        //Создадим задачу
        $notesCollection    = new NotesCollection();
        $serviceMessageNote = new ServiceMessageNote();
        $dto                = $this->dto;
        //TODO factory
        $entityType = $dto->getEntityType();
        switch ($entityType) {
            case  EntityTypesInterface::LEADS:
                $service = $this->apiNotesToLeads;
                break;
            case  EntityTypesInterface::CONTACTS:
                $service = $this->apiNotesToContacts;
                break;
            case  EntityTypesInterface::COMPANIES:
                $service = $this->apiNotesToCompanies;
                break;
            case  EntityTypesInterface::TASKS:
                $service = $this->apiNotesToTasks;
                break;
        }

        try {
            $serviceMessageNote->setEntityId($dto->getEntityId())
                               ->setText($dto->getText())
                               ->setService($dto->getService())
                               ->setCreatedBy($dto->getCreatedBy());

            $notesCollection->add($serviceMessageNote);

            try {
                $notesCollection = $service->add($notesCollection);
                Container::get('app_logger')->save('📃 Сервисное примечание добавлено 💚 по таким параметрам: '
                  .je($serviceMessageNote->toArray()), 'info');
            } catch (AmoCRMApiException $e) {
                $this->logException($e);
            }
        } catch (Exception $e) {
            //TODO
            $this->logException($e);
        }
    }

}