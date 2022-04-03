<?php

namespace Nikacrm\App\Features\Api\Resources;

use Nikacrm\App\Features\Api\Resources\Custom\ShopPartnersResource;
use Nikacrm\App\Features\Api\Resources\Wordpress\MetformResource;
use Nikacrm\Core\Container;

class ResourceFactory
{

    public static function get($requestResourceName = null): RequestResourceInterface
    {
        if (!$requestResourceName) {
            $requestResourceName = Container::get('config')->request_resource;
        }

        switch ($requestResourceName) {
            case 'metform':
                $requestResource = new MetformResource();
                break;
            case 'shop.partners':
                $requestResource = new ShopPartnersResource();
                break;
            default:
                $requestResource = new RequestResource();
        }

        return $requestResource;
    }

}