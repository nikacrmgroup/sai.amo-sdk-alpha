<?php

declare(strict_types=1);


namespace Nikacrm\App\Controllers;


use Nikacrm\App\Features\NoProductsNotifier\NoProductsNotifierFeature;
use Nikacrm\App\Features\Stock\WebhookFeature;
use Nikacrm\Core\Base\Controller;
use Nikacrm\Core\Container;


class WebhooksController extends Controller
{

    public function leadStatus()
    {
        (new WebhookFeature())->updateStatus();
    }

    public function leadUpdate()
    {
        (new WebhookFeature())->leadUpdate();
    }
    public function leadDelete()
    {
        (new WebhookFeature())->deleteUpdate();
    }

}