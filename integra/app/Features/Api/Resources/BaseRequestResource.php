<?php

namespace Nikacrm\App\Features\Api\Resources;

use Nikacrm\Core\Container;

class BaseRequestResource
{

    public function __construct()
    {
        $this->config   = Container::get('config');
        $this->request  = Container::get('request');
        $this->postData = $this->request['POST'];
    }

}