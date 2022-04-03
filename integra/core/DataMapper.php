<?php


namespace Nikacrm\Core;


class DataMapper
{

    private const REQUEST_MAPPER_FILE_EXT = '.mapper.php';


    private const PATH = __DIR__.'/../config/mappers/';


    private $mapper;

    private function __construct($fileName)
    {
        $envConfigs = Container::get('env.configs');

        $this->mapper = $envConfigs[$fileName];
    }

    public function __get($property)
    {
        if (isset($this->mapper[$property])) {
            return $this->mapper[$property];
        }
        if (property_exists($this, $property)) {
            //todo Exception
            return false;
        }
    }

    public function getAll()
    {
        return $this->mapper;
    }

    public static function prepare($fileName): self
    {
        $requestMapper = new self($fileName.self::REQUEST_MAPPER_FILE_EXT);

        //Container::bind('request_mapper', $requestMapper);

        return $requestMapper;
    }

    private function getRequestMapper($fileName)
    {
        $file = self::PATH.$fileName;

        return include($file);
    }
}