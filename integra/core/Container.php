<?php

namespace Nikacrm\Core;

use Exception;
use Throwable;


final class Container
{

    /**
     * All registered keys.
     * @property-read \Nikacrm\Core\Cache cache
     *
     * @property-read \Monolog\Logger logger
     * @property-read \Nikacrm\Core\Config config
     * @var array
     */
    protected static array $registry = [
        //'cache' => Cache::class,
    ];


    /**
     * Bind a new key/value into the container.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public static function bind(string $key, $value)
    {
        static::$registry[$key] = $value;

        return $value;
    }

    /**
     * Retrieve a value from the registry.
     * @param  string  $key
     * @return mixed
     *

     */
    public static function get(string $key)
    {
        try {
            if (!array_key_exists($key, static::$registry)) {
                $message = "No {$key} is bound in the container.";
                //self::get('app_logger')->save($message, 'error');
                throw new Exception($message);
            }


            return static::$registry[$key];
        } catch (Throwable $t) {
            //error_log($t->getMessage(), 3, 'container.exception.log');
            file_put_contents('container.exception.log', $t->getMessage().PHP_EOL, FILE_APPEND);
            //$logger->save($t->getMessage(), 'error');
        }

        return false;
    }
}