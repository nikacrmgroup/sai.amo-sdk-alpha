<?php

declare(strict_types=1);


namespace Nikacrm\Core\Amo\Integration;


use League\OAuth2\Client\Token\AccessToken;


define('TOKEN_FILE', __DIR__.'/../..'.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'token_info.json');

class Token
{

    /**
     * @return AccessToken
     */
    public static function getToken(): AccessToken
    {
        if (!file_exists(TOKEN_FILE)) {
            exit('Access token file not found');
        }

        $accessToken = json_decode(file_get_contents(TOKEN_FILE), true);

        if (
          self::issetToken($accessToken)
        ) {
            return new AccessToken(
              [
                'access_token'  => $accessToken['accessToken'],
                'refresh_token' => $accessToken['refreshToken'],
                'expires'       => $accessToken['expires'],
                'baseDomain'    => $accessToken['baseDomain'],
              ]
            );
        }

        exit('Invalid access token '.var_export($accessToken, true));
    }

    /**
     * @param  array  $accessToken
     */
    public static function saveToken(array $accessToken): void
    {
        if (
          self::issetToken($accessToken)
        ) {
            $data = [
              'accessToken'  => $accessToken['accessToken'],
              'expires'      => $accessToken['expires'],
              'refreshToken' => $accessToken['refreshToken'],
              'baseDomain'   => $accessToken['baseDomain'],
            ];
            //TODO запись storage в папку выше
            $path = __DIR__.'/../..'.DIRECTORY_SEPARATOR.'storage';
            if (!file_exists($path)) {
                if (!mkdir($concurrentDirectory = $path) && !is_dir($concurrentDirectory)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
                }
            }

            file_put_contents(TOKEN_FILE, json_encode($data));
            //file_put_contents(TOKEN_FILE, json_encode($data));
        } else {
            exit('Invalid access token '.var_export($accessToken, true));
        }
    }

    /**
     * @param $accessToken
     * @return bool
     */
    private static function issetToken($accessToken): bool
    {
        return isset(
          $accessToken['accessToken'], $accessToken['refreshToken'],
          $accessToken['expires'], $accessToken['baseDomain']
        );
    }

}