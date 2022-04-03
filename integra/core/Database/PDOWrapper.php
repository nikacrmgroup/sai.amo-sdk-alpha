<?php

namespace Nikacrm\Core\Database;

use Nikacrm\Core\Base\Traits\TSingleton;

class PDOWrapper
{

    use TSingleton;

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

}