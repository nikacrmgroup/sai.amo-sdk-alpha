<?php

namespace Nikacrm\Core\Database;

use Nikacrm\Core\Container;
use Nikacrm\Core\DTO\ConnectionDbDTO;
use PDODb;

class DB
{

    private function __construct()
    {
    }

    public static function prepare()
    {
        $config = Container::get('config');
        if($config->database){
            Container::bind('database', new PDODb(
              Connection::make((new ConnectionDbDTO())->prepareFromConfig()->getParams())
            ));
        }
        else{
            Container::bind('database', []);
        }


    }

}