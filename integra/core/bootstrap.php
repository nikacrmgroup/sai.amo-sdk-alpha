<?php

declare(strict_types=1);

use Nikacrm\Core\{Cache,
  Container,
  Config,
  DTO\ConnectionDbDTO,
  Enums,
  Logger,
  Router,
  Request,
  Session,
  Translate,
  Access,
  TwigTemplater
};
use Nikacrm\Core\Database\{DB, PDOWrapper, QueryBuilder, Connection};

global $scriptStartTime;
$scriptStartTime = microtime(true);

Config::prepare();
Logger::prepare();
Session::prepare();
DB::prepare();
Translate::prepare();
Enums::prepare();
Cache::prepare();
Access::prepare();
TwigTemplater::prepare();

$session = Container::get('session');
$session::generateCsrf();

Container::get('app_logger')->save('🚀 bootstrapped: '.getmypid());
try {
    Router::boot()
          ->direct(Request::uri(), Request::method());
} catch (Throwable $t) {
}

//TODO кеш бутстрапа?

//Request::process();