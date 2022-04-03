<?php

namespace Nikacrm\App\Features\Api\Resources;


use Nikacrm\Core\Container;

class RequestResource extends BaseRequestResource implements RequestResourceInterface
{

    public function getData(): array
    {
        return $this->postData;
    }


}