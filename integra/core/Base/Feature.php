<?php

namespace Nikacrm\Core\Base;

use Nikacrm\Core\Amo\ApiClient;
use Nikacrm\Core\Base\Traits\TLogException;
use Nikacrm\Core\Container;

class Feature
{

    use TLogException;

    protected ApiClient $apiClient;
    /* @var \Nikacrm\Core\Config $config */
    protected $config;
    /**
     * @var \Nikacrm\Core\Logger $logger
     */
    protected $logger;

    public function __construct()
    {
        $this->apiClient = ApiClient::boot();
        $this->config    = Container::get('config');
        $this->logger    = Container::get('app_logger');
    }


}