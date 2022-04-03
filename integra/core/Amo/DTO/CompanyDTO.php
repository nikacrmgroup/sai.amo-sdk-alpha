<?php

namespace Nikacrm\Core\Amo\DTO;

use Nikacrm\Core\Amo\Base\AmoDTO;

class CompanyDTO extends AmoDTO
{

    private string $name;
    private array $customFields;
    private array $tags;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     * @return ContactDTO
     */
    public function setName(string $name): CompanyDTO
    {
        $this->name = $name;

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
     * @return CompanyDTO
     */
    public function setCustomFields(array $customFields): CompanyDTO
    {
        $this->customFields = $customFields;

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
     * @return CompanyDTO
     */
    public function setTags(array $tags): CompanyDTO
    {
        $this->tags = $tags;

        return $this;
    }

}