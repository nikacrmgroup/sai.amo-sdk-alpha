<?php

namespace Nikacrm\App\Features\Api\Mappers;


use Nikacrm\Core\Container;
use Nikacrm\Core\DataMapper;

abstract class BaseMapper
{

    /**
     * @var \Nikacrm\Core\DataMapper
     */
    public DataMapper $amoFieldsMapper;

    /**
     * @var \Nikacrm\Core\Config $config
     */
    public $config;

    /**
     * @var \Nikacrm\Core\DataMapper
     */
    public DataMapper $requestMapper;

    public function __construct()
    {
        $this->requestMapper   = DataMapper::prepare('request');
        $this->amoFieldsMapper = DataMapper::prepare('amo-fields');
        /* @var \Nikacrm\Core\Config $config */
        $this->config = Container::get('config');
    }

    abstract public function prepareDto(array $request, string $type);


}