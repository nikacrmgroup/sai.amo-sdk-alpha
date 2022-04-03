<?php

namespace Nikacrm\Core\Amo\DTO;

use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\TaskModel;
use Exception;
use Nikacrm\Core\Amo\Base\AmoDTO;


class TaskDTO extends AmoDTO
{

    protected $complete_till;
    protected $duration = 30 * 60 * 60; //30 минут
    protected $entity_id;
    protected $entity_type = EntityTypesInterface::LEADS;
    protected $responsible_user_id;
    protected $text = 'Новая задача';
    protected $type_id = TaskModel::TASK_TYPE_ID_CALL;

    /**
     * @throws \Exception
     */
    public function getParams(): array
    {
        if (!$this->responsible_user_id) {
            throw new Exception('No responsible id');
        }
        $params['type_id']             = $this->type_id;
        $params['text']                = $this->text;
        $params['complete_till']       = $this->complete_till ?? strtotime(date('Y-m-d 23:59:59 Z'));
        $params['entity_type']         = $this->entity_type;
        $params['entity_id']           = $this->entity_id;
        $params['duration']            = $this->duration;
        $params['responsible_user_id'] = $this->responsible_user_id;

        return $params;
    }

    /**
     * @param  int  $timestamp
     * @return void
     */
    public function setCompleteTill(int $timestamp): void
    {
        $this->complete_till = $timestamp;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function setEntityId(int $entityId): void
    {
        $this->entity_id = $entityId;
    }

    public function setEntityType($entityType): void
    {
        $this->entity_type = $entityType;
    }

    public function setResponsibleUserId(int $responsibleUserId): void
    {
        $this->responsible_user_id = $responsibleUserId;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function setTypeId(int $id): void
    {
        $this->type_id = $id;
    }

}