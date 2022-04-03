<?php

namespace Nikacrm\Core\Amo\Base;

use Exception;

use Nikacrm\Core\Base\Traits\TLogException;
use Nikacrm\Core\Base\Traits\TProfiler;
use Nikacrm\Core\Container;


abstract class AmoJob ///implements AmoTaskInterface
{

    use TLogException;
    use TProfiler;


    public $apiClient; // Объект, в котором и api клиент амо и его обертка
    protected array $args = [];
    protected ?AmoDTO $dto = null;
    protected $logger;
    protected array $params = [];

    public function __construct(AmoDTO $dto = null)
    {
        /** @var \Nikacrm\Core\Amo\ApiClient $apiClient */
        //TODO переименовать во что более общее
        $this->apiClient = Container::get('api_client');
        $this->logger    = Container::get('logger');
        $this->dto       = $dto;
    }


    public function exec($params = [], ...$args)
    {
        $this->params = $params;
        $this->args   = $args;

        return $this->run([$this, 'logic']);
    }

    abstract protected function logic();

}