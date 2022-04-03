<?php
/** @noinspection PhpIncludeInspection */

namespace Nikacrm\Core;

define('AMO_CONFIG_FILE', 'amo.config.php');
define('SYSTEM_CONFIG_FILE', 'system.config.php');
define('APP_CONFIG_FILE', 'app.config.php');

final class Config
{

    private const AMO_CONFIG_FILE    = 'amo.config.php';
    private const SYSTEM_CONFIG_FILE = 'system.config.php';
    private const APP_CONFIG_FILE    = 'app.config.php';

    private array $amoConfig;
    private array $appConfig;
    private string $cacheConfigPath = __DIR__.'/../config/.cache';
    private $config;
    private string $configPath = __DIR__.'/../config/';
    /**
     * @var false|string
     */
    public string $envType;
    /**
     * @var array|false|mixed|string|void
     */
    private $envConfigs;
    private string $envConfigsPath = __DIR__.'/../config/environment';
    private string $envFileName = '.env.type';
    private string $stateFileExt = '.lock';
    private string $statesPath = __DIR__.'/../states/';
    private array $systemConfig;

    private function __construct()
    {
        $this->envType    = $this->getEnvType();
        $this->envConfigs = $this->getEnvConfigs();

        $this->amoConfig    = $this->getConfig(self::AMO_CONFIG_FILE);
        $this->appConfig    = $this->getConfig(self::APP_CONFIG_FILE);
        $this->systemConfig = $this->getConfig(self::SYSTEM_CONFIG_FILE);


        $this->config = array_merge($this->amoConfig, $this->appConfig, $this->systemConfig,
          $this->envConfigs['request.mapper.php']);
    }

    public function getEnvType()
    {
        $fileName = $this->configPath.DIRECTORY_SEPARATOR.$this->envFileName;
        if (file_exists($fileName)) {
            return file_get_contents($fileName);
        }

        return 'local';
    }

    private function getEnvConfigs()
    {
        $envConfigs = $this->getCachedConfigs();
        //если нет в кеше конфига - берем и объединяем, и пишем в контейнер
        if (!$envConfigs) {
            $envConfigs = $this->getEnvFileConfigs();
        }
        Container::bind('env.configs', $envConfigs);

        return $envConfigs;
    }

    private function getConfig($configFileName)
    {
        return $this->envConfigs[$configFileName] ?? [];

        //$this->cacheConfigs();

        //else
        /* $env        = $this->getEnv();
         $configPath = $this->envConfigsPath.DIRECTORY_SEPARATOR.$env.DIRECTORY_SEPARATOR;
         //todo проверку на пустую папку


         if ($type === 'json') {
             try {
                 return json_decode(
                   file_get_contents($configPath.$configFileName),
                   true,
                   512,
                   JSON_THROW_ON_ERROR
                 );
             } catch (\JsonException $e) {
                 //TODO add error handler
             }
         }
         if ($type === 'php') {
             $file = $configPath.$configFileName;

             return include($file);
         }

         return [];*/
    }

    /*Путь к файлам, которые описывают текущую конфигурацию скрипта, его состояния. Обычно это лок файлы*/

    public function getCachedConfigs()
    {
        $cacheConfigFileName = $this->getCacheConfigFileName();
        if (!file_exists($cacheConfigFileName)) {
            return [];
        }

        $file   = file_get_contents($cacheConfigFileName);
        $config = jd($file);
        $stop   = 'Stop';

        return $config;
    }

    private function getEnvFileConfigs(): array
    {
        $configPath = $this->envConfigsPath.DIRECTORY_SEPARATOR.$this->envType.DIRECTORY_SEPARATOR;

        $files   = array_diff(scandir($configPath), ['..', '.']);
        $configs = [];
        foreach ($files as $fileName) {
            $configFileFullPath = $configPath.$fileName;
            $configs[$fileName] = include $configFileFullPath;
        }

        return $configs;
    }

    /**
     * @return string
     */
    private function getCacheConfigFileName(): string
    {
        return $this->cacheConfigPath.DIRECTORY_SEPARATOR.$this->envType.'.cache.json';
    }

    public function __get($property)
    {
        if (isset($this->config[$property])) {
            return $this->config[$property];
        }
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function updateConfigsCache()
    {
        $configs             = $this->getEnvFileConfigs();
        $cacheConfigFileName = $this->cacheConfigPath.DIRECTORY_SEPARATOR.$this->envType.'.cache.json';

        file_put_contents($cacheConfigFileName, je($configs));
    }

    public function deleteState($name)
    {
        $fileName = $this->statesPath.DIRECTORY_SEPARATOR.$name.$this->stateFileExt;
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }

    public function getAll()
    {
        return $this->config;
    }

    /**
     * @return array|mixed
     */
    public function getAmoConfig()
    {
        return $this->amoConfig;
    }

    /**
     * @return array|mixed
     */
    public function getAppConfig()
    {
        return $this->appConfig;
    }

    public function getState($name)
    {
        $fileName = $this->statesPath.DIRECTORY_SEPARATOR.$name.$this->stateFileExt;
        if (file_exists($fileName)) {
            return file_get_contents($fileName);
        }

        return false;
    }

    /**
     * @return array|mixed
     */
    public function getSystemConfig()
    {
        return $this->systemConfig;
    }

    public static function prepare()
    {
        $config = new Config();
        Container::bind('config', $config);
        self::phpIniSet($config);

        return $config;
    }

    /**
     * Установка ini_set параметров php
     * @return void
     */
    private static function phpIniSet(Config $config)
    {
        $iniParamsArray = $config->php ?? [];
        foreach ($iniParamsArray as $iniName => $iniValue) {
            ini_set($iniName, $iniValue);
        }
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function save($data, $configFileName, $type = 'json'): void
    {
        if ($type === 'json') {
            $data           = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            $configFileName .= '.json';
        }
        if ($type === 'php') {
            $data           = var_export($data, true);
            $configFileName .= '.php';
            $header         = '<?php'."\r\n";
            $data           = $header.$data;
        }
        file_put_contents($this->configPath.$configFileName, $data);
    }

    /**
     * @param $name
     * @param $data
     * @return void
     */
    public function setState($name, $data = null): void
    {
        $fileName = $this->statesPath.DIRECTORY_SEPARATOR.$name.$this->stateFileExt;
        file_put_contents($fileName, je($data));
    }

    private function getEnvConfigPath(): string
    {
        return $this->envConfigsPath.DIRECTORY_SEPARATOR.$this->envType.DIRECTORY_SEPARATOR;
    }
}