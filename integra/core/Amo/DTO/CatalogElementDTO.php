<?php

namespace Nikacrm\Core\Amo\DTO;

use Nikacrm\Core\Amo\Base\AmoDTO;

class CatalogElementDTO extends AmoDTO
{

    private int $id;
    private int $qty = 1;
    private ?int $total;
    private int $price = 0;
    private array $raw = [];

    /**
     * @param  int  $id
     * @return CatalogElementDTO
     */
    public function setId(int $id): CatalogElementDTO
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param  int  $qty
     * @return CatalogElementDTO
     */
    public function setQty(int $qty): CatalogElementDTO
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param  int|null  $total
     * @return CatalogElementDTO
     */
    public function setTotal(?int $total): CatalogElementDTO
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    /**
     * @param  int  $price
     * @return CatalogElementDTO
     */
    public function setPrice(int $price): CatalogElementDTO
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param  array  $raw
     * @return CatalogElementDTO
     */
    public function setRaw(array $raw): CatalogElementDTO
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

}