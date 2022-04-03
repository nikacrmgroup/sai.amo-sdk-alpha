<?php

namespace Nikacrm\Core\DTO;

use Nikacrm\App\Features\Stock\ITransactions;
use Nikacrm\Core\Base\DTO;
use Nikacrm\Core\Container;
use PDO;

class TransactionDTO extends DTO
{

    private string $desc;
    private int $entityId = 0;

    private int $oldResponsibleId = 0;
    private int $responsibleId = 0;

    private int $type = ITransactions::WEBHOOK_TYPE;

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * @param  string  $desc
     * @return TransactionDTO
     */
    public function setDesc(string $desc): TransactionDTO
    {
        $this->desc = $desc;

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
     * @return TransactionDTO
     */
    public function setEntityId(int $entityId): TransactionDTO
    {
        $this->entityId = $entityId;

        return $this;
    }


    /**
     * @return int
     */
    public function getOldResponsibleId(): int
    {
        return $this->oldResponsibleId;
    }

    /**
     * @param  int  $oldResponsibleId
     * @return TransactionDTO
     */
    public function setOldResponsibleId(int $oldResponsibleId): TransactionDTO
    {
        $this->oldResponsibleId = $oldResponsibleId;

        return $this;
    }

    /**
     * @return int
     */
    public function getResponsibleId(): int
    {
        return $this->responsibleId;
    }

    /**
     * @param  int  $responsibleId
     * @return TransactionDTO
     */
    public function setResponsibleId(int $responsibleId): TransactionDTO
    {
        $this->responsibleId = $responsibleId;

        return $this;
    }


    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param  int  $type
     * @return TransactionDTO
     */
    public function setType(int $type): TransactionDTO
    {
        $this->type = $type;

        return $this;
    }


}