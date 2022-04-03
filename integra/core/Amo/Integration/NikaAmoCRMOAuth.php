<?php

namespace Nikacrm\Core\Amo\Integration;

use AmoCRM\OAuth\AmoCRMOAuth;
use GuzzleHttp\ClientInterface;

class NikaAmoCRMOAuth extends AmoCRMOAuth
{
    protected const REQUEST_TIMEOUT = 60;

    public function __construct(string $clientId, string $clientSecret, ?string $redirectUri)
    {
        parent::__construct($clientId, $clientSecret, $redirectUri);

    }

    public function getHttpClient(): ClientInterface
    {
        $httpClient  = parent::getHttpClient();
        return $httpClient;
    }


}