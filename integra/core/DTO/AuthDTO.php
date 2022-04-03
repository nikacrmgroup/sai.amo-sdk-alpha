<?php

namespace Nikacrm\Core\DTO;


use Nikacrm\Core\Base\DTO;

class AuthDTO extends DTO
{

    private string $clientId;
    private string $login;
    private string $md5string;
    private string $password;
    private string $rawPassword;

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param  string  $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param  string  $login
     * @return AuthDTO
     */
    public function setLogin(string $login): AuthDTO
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getMd5string(): string
    {
        $this->md5string = md5($this->clientId.$this->login.$this->password);

        return $this->md5string;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param  string  $password
     * @return AuthDTO
     */
    public function setPassword(string $password): AuthDTO
    {
        $this->password    = hash_password($password);
        $this->rawPassword = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getRawPassword(): string
    {
        return $this->rawPassword;
    }

}