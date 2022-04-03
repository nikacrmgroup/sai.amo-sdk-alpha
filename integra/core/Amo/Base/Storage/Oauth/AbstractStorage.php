<?php

declare(strict_types=1);

namespace Nikacrm\Core\Amo\Base\Storage\Oauth;

use Nikacrm\Core\Amo\Oauthapi;

class AbstractStorage
{

    protected static $_oauth = [];
    protected $options = [];

    /**
     * Constructor
     * @param  array  $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Get oauth data
     * @param  Oauthapi  $client
     * @param  string|null  $field
     * @return array
     */
    public function getOauthData(Oauthapi $client, $field = null): array
    {
        $key = $client->getAuth('domain').'_'.$client->getAuth('client_id');
        if (!array_key_exists($key, static::$_oauth)) {
            $this->initClient($client);
        }
        if (!is_null($field) && array_key_exists($field, static::$_oauth[$key])) {
            return static::$_oauth[$key][$field];
        }

        return static::$_oauth[$key];
    }

    /**
     * Set oauth data
     * @param  Oauthapi  $client
     * @param  array  $oauth
     * @return bool
     */
    public function setOauthData(Oauthapi $client, array $oauth): bool
    {
        $key = $client->getAuth('domain').'_'.$client->getAuth('client_id');
        if (!array_key_exists($key, static::$_oauth)) {
            $this->initClient($client);
        }
        static::$_oauth[$key] = $oauth;

        return true;
    }

    /**
     * Init oauth handler
     * @param  Oauthapi  $client
     * @return void
     */
    protected function initClient(Oauthapi $client)
    {
        $key                  = $client->getAuth('domain').'_'.$client->getAuth('client_id');
        static::$_oauth[$key] = [
          'token_type'    => '',
          'expires_in'    => 0,
          'access_token'  => '',
          'refresh_token' => '',
          'created_at'    => 0,
        ];
    }
}
