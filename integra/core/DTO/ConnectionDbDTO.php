<?php

namespace Nikacrm\Core\DTO;

use Nikacrm\Core\Base\DTO;
use Nikacrm\Core\Container;
use PDO;

class ConnectionDbDTO extends DTO
{

    private $connection;
    private $name;
    private $options;
    private $password;
    private $username;

    public function getParams(): array
    {
        $params['name']       = $this->name;
        $params['username']   = $this->username;
        $params['password']   = $this->password;
        $params['connection'] = $this->connection ?? 'mysql:host=127.0.0.1';
        $params['options']    = $this->options;

        return $params;
    }

    /**
     * @param  string|null  $databaseName
     * @return \Nikacrm\Core\DTO\ConnectionDbDTO
     */
    public function prepareFromConfig(string $databaseName = null): ConnectionDbDTO
    {
        $config = Container::get('config');

        if (!$databaseName) {
            //$env      = Container::get('config')->envType;
            $dbConfig = Container::get('config')->database;
        } else {
            $dbConfig = Container::get('config')->database[$databaseName];
        }
        $this->setName($dbConfig['name']);
        $this->setPassword($dbConfig['password']);
        $this->setUsername($dbConfig['username']);
        $this->setOptions($dbConfig['options'] ?? []);
        $this->setConnection($dbConfig['connection'] ?? 'mysql:host=127.0.0.1');

        return $this;
    }

    /**
     * @param  string|null  $connection
     * @return void
     */
    public function setConnection(string $connection = null): void
    {
        $this->connection = $connection;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param  array  $options
     * @return void
     */
    public function setOptions(array $options = []): void
    {
        $defaultOptions = [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ];

        $this->options = $options ?? $defaultOptions;
    }

    /**
     * @param  string  $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param  string  $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

}