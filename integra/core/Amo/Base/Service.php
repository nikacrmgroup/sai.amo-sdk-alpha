<?php

declare(strict_types=1);

namespace Nikacrm\Core\Amo\Base;

use Nikacrm\Core\Amo\ApiClient;

use Nikacrm\Core\Container;


/**
 * @property ApiClient $instance
 */
class Service
{

    protected static $_service_instances = [];
    protected $apiClient;
    protected $api_args = [];
    public $client_id;

    /**
     * Constructor
     * @param  string  $client_id
     * @throws \Exception
     */
    protected function __construct(string $client_id)
    {
        $this->client_id = $client_id;
        $this->apiClient = Container::get('api_client')->client;
        $this->_boot();
    }

    /**
     * Get api method
     * @param  string  $target
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $target)
    {
        if (isset($this->{$target})) {
            return $this->{$target};
        }
        $apiClass = Container::get('api_client');

        if ($target === 'instance') {
            return $apiClass::getInstance($this->client_id);
        }
    }

    /**
     * Get class basename
     * @return string
     */
    public static function getBasename(): string
    {
        return substr(static::class, strrpos(static::class, '\\') + 1);
    }

    /**
     * Get service instance
     * @param  null  $name  Service name
     * @param  \Nikacrm\Core\Amo\ApiClient  $instance
     * @return Service
     */
    public static function getInstance($name = null, ApiClient $instance): ?Service
    {
        if (is_null($name)) {
            $name = lcfirst(static::getBasename());
        }
        $key = $name.'-'.$instance->getAuth('id');

        return static::$_service_instances[$key] ?? null;
    }

    /**
     * Set service instance
     * @param  string  $name  Service name
     * @param  ApiClient  $instance
     * @return Service
     */
    public static function setInstance(string $name, ApiClient $instance, $args = []): ?Service
    {
        if (is_null($name)) {
            $name = lcfirst(static::getBasename());
        }
        $key = $name.'-'.$instance->getAuth('id');
        if (!isset(static::$_service_instances[$key])) {
            static::$_service_instances[$key] = new static($instance->getAuth('id'), $args);
        }

        return static::getInstance($name, $instance);
    }


    /**
     * Service on load
     * @return void
     */
    protected function _boot()
    {
    }


}