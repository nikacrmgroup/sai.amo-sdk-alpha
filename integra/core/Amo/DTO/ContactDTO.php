<?php

namespace Nikacrm\Core\Amo\DTO;

use Nikacrm\Core\Amo\Base\AmoDTO;

class ContactDTO extends AmoDTO
{

    private array $customFields;
    private string $firstName;
    private ?bool $isMain;
    private ?int $id;
    private string $lastName;
    private string $name;
    private ?array $tags;

    /**
     * @param  bool|null  $isMain
     * @return ContactDTO
     */
    public function setIsMain(?bool $isMain): ContactDTO
    {
        $this->isMain = $isMain;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsMain(): ?bool
    {
        return $this->isMain;
    }

    /**
     * @param  int|null  $id
     * @return ContactDTO
     */
    public function setId(?int $id): ContactDTO
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return ContactDTO
     */
    public function setCustomFields(array $customFields): ContactDTO
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName ?? '';
    }

    /**
     * @param  string  $firstName
     * @return ContactDTO
     */
    public function setFirstName(string $firstName): ContactDTO
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName ?? $this->name;
    }

    /**
     * @param  string  $lastName
     * @return ContactDTO
     */
    public function setLastName(string $lastName): ContactDTO
    {
        $this->lastName = $lastName;

        return $this;
    }

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
    public function setName(string $name): ContactDTO
    {
        $this->name = $name;

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
     * @return ContactDTO
     */
    public function setTags(array $tags): ContactDTO
    {
        $this->tags = $tags;

        return $this;
    }

}