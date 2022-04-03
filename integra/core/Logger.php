<?php

namespace Nikacrm\Core;

use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

use Monolog\Logger as MonoLogger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;

class Logger

{

    private const MAX_LOG_FILES = 20;
    /**
     * @var \Nikacrm\Core\Config $config
     */
    private $config;
    private string $logPath = __DIR__.'/../logs/';
    private string $logType;
    private MonoLogger $loggerObj;

    private function __construct(
      array $params = [],
      string $logType = 'info'
    ) {
        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }

        $scriptName = $params['name'] ?? '';
        if ($scriptName === '' && isset($_SERVER['SCRIPT_NAME'])) {
            $scriptName = explode('/', str_replace('.php', '', $_SERVER['SCRIPT_NAME']));
            $scriptName = implode('_', array_filter($scriptName));
            //TODO переделать под одно имя файла, без пути
        }
        $defaultParams = [
          'channel_name' => $scriptName,
        ];
        $this->logType = $logType;

        $params          = array_merge($defaultParams, $params);
        $this->loggerObj = new MonoLogger($params['channel_name']);
        $this->config    = Container::get('config');
        $maxFiles        = $this->config->max_log_files ?? self::MAX_LOG_FILES;
        try {
            $handler = new RotatingFileHandler(
              $this->logPath.$params['channel_name'].'.log',
              $maxFiles, MonoLogger::DEBUG, true, 0664
            );

            $formatter = new LineFormatter(null, null, true, true);
            $handler->setFormatter($formatter);

            //$handler->pushProcessor(new MemoryUsageProcessor());
            $handler->pushProcessor(new MemoryPeakUsageProcessor());
            $handler->pushProcessor(new ProcessIdProcessor());
            $handler->pushProcessor(new IntrospectionProcessor(MonoLogger::DEBUG, [], 1));

            //$handler->pushProcessor(new WebProcessor());

            $this->loggerObj->pushHandler($handler);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public static function prepare(): void
    {
        $channels = Container::get('config')->logger_channels;
        foreach ($channels as $containerName => $channelName) {
            Container::bind($containerName, self::start(['channel_name' => $channelName]));
        }
        //Container::bind('request_logger', Logger::start(['channel_name' => 'request-logger']));
        //Container::bind('app_logger', Logger::start(['channel_name' => 'app-logger']));
        //Container::bind('logger', Logger::start(['channel_name' => 'all-logger']));
        //Container::bind('profiling_logger', Logger::start(['channel_name' => 'profiling']));

    }

    public static function start(
      array $params = [],
      string $logType = 'info'
    ): Logger {
        return new Logger($params, $logType);
    }

    /**
     * Обертка монолога
     * @param $message
     * @param  null  $logType
     */
    public function save($message, $logType = null): void
    {
        if (!$logType) {
            $logType = $this->logType;
        }
        if ($logType === 'debug' && !$this->config->debug) {
            return;
        }
        $this->loggerObj->$logType($message);
    }

}