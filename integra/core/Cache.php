<?php


namespace Nikacrm\Core;

use Nikacrm\Core\Base\Traits\TLogException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Phpfastcache\Helper\Psr16Adapter;

class Cache
{

    use TLogException;

    protected const DEFAULT_TTL = 300; //5 min
    private $adapter;

    private $appLogger;

    private $config;

    /**
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverCheckException
     * @throws \ReflectionException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheLogicException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    private function __construct()
    {
        $defaultDriver   = 'Files';
        $this->adapter   = new Psr16Adapter($defaultDriver);
        $this->appLogger = Container::get('app_logger');
        $this->config    = Container::get('config');
    }


    /**
     * @return bool
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     */
    public function clear(): bool
    {
        $this->appLogger->save("🧼 Все кеши очищены!");

        return $this->adapter->clear();
    }

    public function get(string $key)
    {
        if (!$this->adapter->has($key)) {
            return false;
        }

        return $this->adapter->get($key);
    }

    public function deleteItem(string $key) : bool
    {
        if (!$this->adapter->has($key)) {
            return false;
        }

        return $this->adapter->delete($key);
    }

    /**
     * @param  string  $cacheKey
     * @param  array  $callbackArray  Массив колбека, где первый элемент - это объект, второй - метод строкой
     * @param  int|null  $ttl
     * @return false|mixed|null
     */
    public function getCachedData(string $cacheKey, array $callbackArray, int $ttl = null)
    {
        /**
         * @var \Nikacrm\Core\Cache $cache
         */
        //$cache = Container::get('cache');

        $cacheDesc    = $this->config->cache[$cacheKey]['desc'] ?? '';
        $cacheKeyUUID = $this->prepareCacheName($cacheKey);

        $data = $this->get($cacheKeyUUID);
        if (!$data) {
            $this->appLogger->save("💙🌵 Старт обновления кеша [{$cacheKey}][{$cacheDesc}][{$cacheKeyUUID}]");
            $data = call_user_func([$callbackArray[0], $callbackArray[1]]);

            $this->set($cacheKey, $cacheKeyUUID, $data, $ttl);
            $this->appLogger->save("💚🌵 Кеш [{$cacheKey}][{$cacheDesc}][{$cacheKeyUUID}] обновлен, первые 10000 символов равны: ".
              substr(je
              ($data),
                0, 10000), 'debug');
        } else {
            $this->appLogger->save("♻ Кеш [$cacheKey][{$cacheDesc}][{$cacheKeyUUID}] нет нужды обновлять, первые 100 символов равны: ".
              substr(je
              ($data),
                0, 100), 'debug');
        }


        return $data;
    }

    public function getTtlFromConfig($cacheKey)
    {
        return $this->config->cache[$cacheKey]['ttl'] ?? false;
    }

    public static function prepare()
    {
        Container::bind('cache', new self());
    }

    /**
     * Создает уникальный для приложения ключ кеша
     * @param $name
     * @return string
     */
    public function prepareCacheName($name): string
    {
        //https://github.com/PHPSocialNetwork/phpfastcache/wiki/%5BV6%CB%96%5D-Unsupported-characters-in-key-identifiers
        $name = str_replace(['@', '.', '{', '}', '(', ')', '/', '\\', '@', ':'], '_', $name);
        $uuid = $this->config->client_id;

        return "{$name}_{$uuid}";
    }

    /**
     * @param  string  $configCacheKey  Ключ кеша из конфига
     * @param  string  $cacheKey  Ключ кеша с UUID
     * @param  mixed  $data  Данные кеша
     * @param  integer | null  $ttl  Время жизни кеша в секундах, возможно определение из конфига по ключу
     * @return void
     */
    public function set(string $configCacheKey, string $cacheKey, $data, int $ttl = null): void
    {
        /*Смотрим, есть ли в конфиге для ключа время кеша*/
        $ttlFromConfig = $this->getTtlFromConfig($configCacheKey);
        if ($ttlFromConfig) {
            $preparedTtl = $ttlFromConfig;
        } else {
            //если нет, то берем дефолтное время из action, а если и его нет - то дефолтное самого кеша
            $preparedTtl = $ttl ?? self::DEFAULT_TTL;
        }
        try {
            $this->adapter->set($cacheKey, $data, $preparedTtl);
        } catch (PhpfastcacheSimpleCacheException|\Psr\Cache\InvalidArgumentException $e) {
            $this->logException($e);
        }
    }


}