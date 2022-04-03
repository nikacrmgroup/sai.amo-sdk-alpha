<?php

namespace Nikacrm\Core\Amo\DTO\Notes;

use Exception;
use Nikacrm\Core\Amo\Base\AmoDTO;


class ServiceNoteDTO extends AmoDTO
{

    /*
     * setEntityId(1)
    ->setText('Текст примечания')
    ->setService('Api Library')
    ->setCreatedBy(0);
     * */
    private int $createdBy;
    private int $entityId;
    private int $entityType;
    private string $service;
    private string $text;

    /**
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    /**
     * @param  int  $createdBy
     * @return ServiceNoteDTO
     */
    public function setCreatedBy(int $createdBy): ServiceNoteDTO
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @param  int  $entityId
     * @return ServiceNoteDTO
     */
    public function setEntityId(int $entityId): ServiceNoteDTO
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntityType(): int
    {
        return $this->entityType;
    }

    /**
     * @param  int  $entityType
     * @return ServiceNoteDTO
     */
    public function setEntityType(int $entityType): ServiceNoteDTO
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param  string  $service
     * @return ServiceNoteDTO
     */
    public function setService(string $service): ServiceNoteDTO
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param  string  $text
     * @return ServiceNoteDTO
     */
    public function setText(string $text): ServiceNoteDTO
    {
        $this->text = $text;

        return $this;
    }


}