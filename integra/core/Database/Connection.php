<?php

namespace Nikacrm\Core\Database;

use PDO;
use PDODb;
use PDOException;

class Connection
{

    /**
     * Create a new PDO connection.
     *
     * @param  array  $config
     */
    public static function make(array $config)
    {
        try {
            return new PDO(
              $config['connection'].';dbname='.$config['name'].';charset=utf8',
              $config['username'],
              $config['password'],
              $config['options'],

            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


}