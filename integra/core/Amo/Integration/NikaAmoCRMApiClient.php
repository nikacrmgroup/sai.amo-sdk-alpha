<?php

namespace Nikacrm\Core\Amo\Integration;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\AmoCRMApiRequest;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use League\OAuth2\Client\Token\AccessToken;
use ReflectionObject;

//TODO нужно переопределять клиент гуззла с middleware
//https://github.com/amocrm/amocrm-api-php/issues/398
// https://github.com/caseyamcl/guzzle_retry_middleware
// https://dev.to/romalyt/how-to-create-robust-http-clients-with-guzzle-34p6
// https://divinglaravel.com/always-set-a-timeout-for-guzzle-requests-inside-a-queued-job
class NikaAmoCRMApiClient extends \AmoCRM\Client\AmoCRMApiClient
{

    /**
     * Метод строит объект для совершения запросов для сервисов сущностей
     *
     * @return \Nikacrm\Core\Amo\Integration\NikaAmoCRMApiRequest
     * @throws AmoCRMMissedTokenException
     */
    private function buildRequest(): NikaAmoCRMApiRequest
    {
        $r = new ReflectionObject(AmoCRMApiClient::class);
        $accessTokenRefreshCallback = $r->getProperty('accessTokenRefreshCallback');
        $accessTokenRefreshCallback->setAccessible(true);
        $accessToken = $r->getProperty('accessToken');
        $accessToken->setAccessible(true);


        if (!$this->isAccessTokenSet()) {
            throw new AmoCRMMissedTokenException();
        }

        $oAuthClient = $this->getOAuthClient();

        $oAuthClient->setAccessTokenRefreshCallback(
          function (AccessToken $accessToken, string $baseAccountDomain) use (
            $accessTokenRefreshCallback,
            $oAuthClient
          ) {
              $this->setAccessToken($accessToken);

              if (is_callable($accessTokenRefreshCallback)) {
                  $callback = $accessTokenRefreshCallback;
                  $callback($accessToken, $baseAccountDomain);
              }
          }
        );

        /*Проблема с тем, что свойства и методы -private, замаешься через рефлексию все тянуть(*/

        return new NikaAmoCRMApiRequest($accessToken, $oAuthClient);
        //return new AmoCRMApiRequest($accessToken, $oAuthClient);
    }

}