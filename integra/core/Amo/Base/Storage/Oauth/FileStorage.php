<?php

declare(strict_types=1);

namespace Nikacrm\Core\Amo\Base\Storage\Oauth;

use Nikacrm\Core\Amo\Oauthapi;

class FileStorage extends AbstractStorage
{

    /**
     * Constructor
     * @param  array  $options
     * @throws \Exception
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        if (empty($this->options['path'])) {
            throw new \Exception('File Storage options[path] must be string path');
        }
    }

    /**
     * Set oauth data
     * @param  Oauthapi  $client
     * @param  array  $oauth
     * @return bool
     * @throws \JsonException
     */
    public function setOauthData(Oauthapi $client, array $oauth): bool
    {
        parent::setOauthData($client, $oauth);

        return file_put_contents(
          $this->options['path'].'/'.$client->getAuth('domain').'/'.$client->getAuth('client_id').'.json',
          json_encode($oauth, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * Init oauth handler
     * @param  Oauthapi  $client
     * @return void
     * @throws \JsonException
     */
    protected function initClient(Oauthapi $client): void
    {
        parent::initClient($client);

        if (!file_exists($this->options['path'].'/'.$client->getAuth('domain')) && !mkdir(
            $concurrentDirectory = $this->options['path'].'/'.$client->getAuth('domain'),
            0777,
            true
          ) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        if (file_exists(
          $this->options['path'].'/'.$client->getAuth('domain').'/'.$client->getAuth('client_id').'.json'
        )) {
            $key                  = $client->getAuth('domain').'_'.$client->getAuth('client_id');
            static::$_oauth[$key] = json_decode(
              file_get_contents(
                $this->options['path'].'/'.$client->getAuth('domain').'/'.$client->getAuth('client_id').'.json'
              ),
              true,
              512,
              JSON_THROW_ON_ERROR
            );
        }
    }
}
