<?php

declare(strict_types=1);

namespace Nikacrm\Core\Amo;


use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Nikacrm\Core\Amo\Integration\Token;

use Nikacrm\Core\Container;

/**
 * @method \Nikacrm\Core\Amo\Services\LeadsService leads()
 * @method \Nikacrm\Core\Amo\Services\CatalogsService catalogs()
 * @method \Nikacrm\Core\Amo\Services\CatalogElementsService catalogElements()
 * @method \Nikacrm\Core\Amo\Services\CustomFieldGroupsService customFieldGroupsService()
 */
class ApiClient
{

    //protected $_account;
    protected static array $_instances = [];

    protected array $accountConfig;
    public AmoCRMApiClient $client;
    protected array $services = [
      'AccountService',
      'PipelinesService',
      'StatusesService',
      'UnsortedService',
      'LeadsService',
      'ContactsService',
      'CompaniesService',
      'LinksService',
      'ProductsService',
      'CatalogsService',
      'CatalogElementsService',
      'CustomFieldGroupsService',
      'CustomFieldsService',
      'TasksService',
      'TagsService',
      'NotesToLeadsService',
      'NotesToCompaniesService',
      'NotesToContactsService',
    ];

    private function __construct()
    {
        $config       = Container::get('config');
        $clientId     = $config->client_id;
        $clientSecret = $config->client_secret;
        $redirectUri  = $config->redirect_uri;
        $this->client = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        $this->accountConfig = [
          'domain'        => $config->domain,
          'client_id'     => $clientId, // id приложения
          'client_secret' => $clientSecret,
          'redirect_uri'  => $redirectUri,
        ];
        $accessToken         = Token::getToken();
        //Oauthapi::setInstance($this->accountConfig);

        $this->client->setAccessToken($accessToken)
                     ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
                     ->onAccessTokenRefresh(
                       function (AccessTokenInterface $accessToken, string $baseDomain) {
                           Token::saveToken(
                             [
                               'accessToken'  => $accessToken->getToken(),
                               'refreshToken' => $accessToken->getRefreshToken(),
                               'expires'      => $accessToken->getExpires(),
                               'baseDomain'   => $baseDomain,
                             ]
                           );
                       }
                     );
    }

    /**
     * Call Service Methods
     * @param  string  $service_name
     * @param  array  $args
     * @return mixed
     * @throws \Exception
     */
    public function __call(string $service_name, $args = [])
    {
        $uc = ucfirst($service_name);
        if (!in_array($uc, $this->services)) {
            throw new \Exception('Invalid service called: '.$service_name);
        }
        $service_class = '\\Nikacrm\\Core\\Amo\\Services\\'.ucfirst($service_name);
        if (!$service = $service_class::getInstance($service_name, $this)) {
            $service = $service_class::setInstance($service_name, $this, ...$args);
        }

        return $service;
    }

    /**
     * Get Service
     * @param  string  $target
     * @throws \Exception
     */
    public function __get($target)
    {
        if (!in_array($target, $this->services, true)) {
            throw new \Exception('Invalid service called: '.$target);
        }
        $service_class = '\\Nikacrm\\Core\\Amo\\'.ucfirst($target);
        if (!$service = $service_class::getInstance($target, $this)) {
            $service = $service_class::setInstance($target, $this);
        }
        if (!method_exists($service, $target)) {
            throw new \Exception('Invalid service method called: '.$target.'()');
        }

        return $service->{$target}();
    }


    public static function boot(): ApiClient
    {
        $apiClient = new ApiClient();
        Container::bind('api_client', $apiClient);

        return $apiClient;
    }

    /**
     * Get account data
     * @param  string|null  $key
     * @return string|array
     */
    public function getAuth($key = null)
    {
        if ($key === 'id') {
            return $this->accountConfig['client_id'];
        }
        if (!is_null($key) && isset($this->accountConfig[$key])) {
            return $this->accountConfig[$key];
        }

        return $this->accountConfig;
    }

    /**
     * Get account instance
     * @param  string  $client_id
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance(string $client_id)
    {
        if (!isset(self::$_instances[$client_id])) {
            throw new Exception('Account not found: '.$client_id);
        }

        return self::$_instances[$client_id];
    }

    /**
     * Has account isset
     * @param  string  $client_id
     * @return bool
     */
    public static function hasInstance($client_id)
    {
        return isset(self::$_instances[$client_id]);
    }

    /** Remove account instance
     * @param $client_id
     */
    public static function removeInstance($client_id)
    {
        if (isset(self::$_instances[$client_id])) {
            self::$_instances[$client_id] = null;
        }
        unset(self::$_instances[$client_id]);
    }

}