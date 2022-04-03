<?php

namespace Nikacrm\Core\Amo\Factories;

use Nikacrm\Core\Base\Traits\TLogException;

abstract class AmoFactory
{
    use TLogException;

    abstract public function get(array $data);

}