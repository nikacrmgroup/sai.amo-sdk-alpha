<?php

namespace Nikacrm\Core\Amo\DTO;

use Nikacrm\Core\Amo\Base\AmoDTO;

class LeadComplexDTO extends AmoDTO
{

    private $companyDto;
    private string $companyName = '';
    private $contactDto;
    private string $contactName = '';
    private array $customFields = [];
    private string $leadName = '';
    private int $pipelineId = 0;
    private int $price = 0;
    private int $statusId = 0;
    private array $tags = [];

    public function getCompanyDto()
    {
        if ($this->companyDto && !$this->companyDto->getCustomFields()) {
            $this->companyDto->setCustomFields($this->customFields);
        }

        return $this->companyDto;
    }

    /**
     * @param  \Nikacrm\Core\Amo\DTO\CompanyDTO  $companyDto
     * @return LeadComplexDTO
     */
    public function setCompanyDto(CompanyDTO $companyDto): LeadComplexDTO
    {
        $this->companyDto = $companyDto;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param  string  $companyName
     * @return LeadComplexDTO
     */
    public function setCompanyName(string $companyName): LeadComplexDTO
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return \Nikacrm\Core\Amo\DTO\ContactDTO
     */
    public function getContactDto(): ContactDTO
    {
        if (!$this->contactDto) {
            $this->contactDto = new ContactDTO();
        }
        if (!$this->contactDto->getCustomFields()) {
            $this->contactDto->setCustomFields($this->customFields);
        }

        return $this->contactDto;
    }

    /**
     * @param  \Nikacrm\Core\Amo\DTO\ContactDTO  $contactDto
     * @return LeadComplexDTO
     */
    public function setContactDto(ContactDTO $contactDto): LeadComplexDTO
    {
        $this->contactDto = $contactDto;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactName(): string
    {
        return $this->contactName;
    }

    /**
     * @param  string  $contactName
     * @return LeadComplexDTO
     */
    public function setContactName(string $contactName): LeadComplexDTO
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    /**
     * @param  array  $customFields
     * @return LeadComplexDTO
     */
    public function setCustomFields(array $customFields): LeadComplexDTO
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * @return string
     */
    public function getLeadName(): string
    {
        return $this->leadName;
    }

    /**
     * @param  string  $leadName
     * @return LeadComplexDTO
     */
    public function setLeadName(string $leadName): LeadComplexDTO
    {
        $this->leadName = $leadName;

        return $this;
    }

    /**
     * @return int
     */
    public function getPipelineId(): int
    {
        return $this->pipelineId;
    }

    /**
     * @param  int  $pipelineId
     * @return LeadComplexDTO
     */
    public function setPipelineId(int $pipelineId): LeadComplexDTO
    {
        $this->pipelineId = $pipelineId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price ?? 0;
    }

    /**
     * @param  int  $price
     * @return LeadComplexDTO
     */
    public function setPrice(int $price): LeadComplexDTO
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @param  int  $statusId
     * @return LeadComplexDTO
     */
    public function setStatusId(int $statusId): LeadComplexDTO
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param  array  $tags
     * @return LeadComplexDTO
     */
    public function setTags(array $tags): LeadComplexDTO
    {
        $this->tags = $tags;

        return $this;
    }


}