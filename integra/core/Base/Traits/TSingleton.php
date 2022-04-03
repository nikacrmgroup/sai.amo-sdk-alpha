<?php

namespace Nikacrm\Core\Base\Traits;

trait TSingleton
{

    protected static $instance;

    final private function __construct()
    {
        $this->init();
    }

    final public static function instance()
    {
        return static::$instance ?? static::$instance = new static;
    }

    final private function __clone()
    {
    }

    final private function __wakeup()
    {
    }

    protected function init()
    {
    }
}