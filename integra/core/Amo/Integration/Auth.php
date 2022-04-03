<?php

declare(strict_types=1);


namespace Nikacrm\Core\Amo\Integration;


use AmoCRM\Client\AmoCRMApiClient;

use Nikacrm\Core\Container;
use Throwable;

class Auth
{

    public static function run()
    {
        //session_start();
        $logger = Container::get('logger');
        $config = Container::get('config');

        $clientId     = $config->client_id;
        $clientSecret = $config->client_secret;
        $redirectUri  = $config->redirect_uri;

        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        if (isset($_GET['referer'])) {
            $apiClient->setAccountBaseDomain($_GET['referer']);
        }

        if (!isset($_GET['code'])) {
            $state                   = bin2hex(random_bytes(16));
            $_SESSION['oauth2state'] = $state;
            if (isset($_GET['button'])) {
                echo $apiClient->getOAuthClient()->getOAuthButton(
                  [
                    'title'          => 'Установить интеграцию',
                    'compact'        => true,
                    'class_name'     => 'className',
                    'color'          => 'default',
                    'error_callback' => 'handleOauthError',
                    'state'          => $state,
                  ]
                );
                die;
            }

            $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl(
              [
                'state' => $state,
                'mode'  => 'post_message',
              ]
            );
            header('Location: '.$authorizationUrl);
            die;
        }

        if (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            $logger->save('Не прошли проверку 😪 '.json_encode($_SERVER), 'error');
            exit('Invalid state');
        }

        /**
         * Ловим обратный код
         */
        try {
            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);

            if (!$accessToken->hasExpired()) {
                Token::saveToken(
                  [
                    'accessToken'  => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires'      => $accessToken->getExpires(),
                    'baseDomain'   => $apiClient->getAccountBaseDomain(),
                  ]
                );
            }
        } catch (Throwable $e) {
            $logger->save($e->getMessage(), 'error');
            die((string) $e);
        }

        $ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);

        //TODO Авторизация прошла успешно
        //printf('Авторизация прошла успешно, %s!', $ownerDetails->getName());
        view('auth_response', ['owner' => $ownerDetails->getName()]);
        //session_regenerate_id(true);
    }


}