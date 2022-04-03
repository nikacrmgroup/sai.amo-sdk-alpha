<?php

namespace Nikacrm\Core\Amo\DTO;

use AmoCRM\Collections\CatalogElementsCollection;
use Nikacrm\Core\Amo\Base\AmoDTO;

class LeadDTO extends AmoDTO
{

    private array $customFields = [];
    private string $leadName = '';
    private int $pipelineId = 0;
    private int $price = 0;
    private int $statusId = 0;
    private array $tags = [];
    private array $linkedContactIds = [];

    private int $linkedCompanyId = 0;
    private array $linkedCatalogElementsDto = [];
    private ?CatalogElementsCollection $linkedCatalogElementsCollection = null;


    public function setLinkedCatalogElementsCollection(CatalogElementsCollection $linkedCatalogElementsCollection
    ): LeadDTO {
        $this->linkedCatalogElementsCollection = $linkedCatalogElementsCollection;

        return $this;
    }

    public function getLinkedCatalogElementsCollection(): ?CatalogElementsCollection
    {
        return $this->linkedCatalogElementsCollection;
    }

    /**
     * @param  array  $linkedCatalogElementsDto
     * @return LeadDTO
     */
    public function setLinkedCatalogElementsDto(array $linkedCatalogElementsDto): LeadDTO
    {
        $this->linkedCatalogElementsDto = $linkedCatalogElementsDto;

        return $this;
    }

    /**
     * @return array
     */
    public function getLinkedCatalogElementsDto(): array
    {
        return $this->linkedCatalogElementsDto;
    }


    /**
     * @param  array  $linkedContactIds
     * @return LeadDTO
     */
    public function setLinkedContactIds(array $linkedContactIds): LeadDTO
    {
        $this->linkedContactIds = $linkedContactIds;

        return $this;
    }

    /**
     * @return array
     */
    public function getLinkedContactIds(): array
    {
        return $this->linkedContactIds;
    }

    /**
     * @param  int  $linkedCompanyId
     * @return LeadDTO
     */
    public function setLinkedCompanyId(int $linkedCompanyId): LeadDTO
    {
        $this->linkedCompanyId = $linkedCompanyId;

        return $this;
    }

    /**
     * @return int
     */
    public function getLinkedCompanyId(): int
    {
        return $this->linkedCompanyId;
    }


    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields): LeadDTO
    {
        $this->customFields = $customFields;

        return $this;
    }

    public function getLeadName(): string
    {
        return $this->leadName;
    }

    public function setLeadName(string $leadName): LeadDTO
    {
        $this->leadName = $leadName;

        return $this;
    }

    public function getPipelineId(): int
    {
        return $this->pipelineId;
    }

    public function setPipelineId(int $pipelineId)
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price ?? 0;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;

        return $this;
    }

    public function getStatusId(): int
    {
        return $this->statusId;
    }

    public function setStatusId(int $statusId): LeadDTO
    {
        $this->statusId = $statusId;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }


    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }


}