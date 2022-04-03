<?php

//namespace Nikacrm\Core\Helpers;

use AmoCRM\Exceptions\AmoCRMApiException;
use Nikacrm\Core\Container;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Require a view.
 *
 * @param  string  $name
 * @param  array  $data
 * @return mixed
 */
function view(string $name, $param = [])
{
    extract($param);

    return require "views/php/{$name}.view.php";
}

function check_access()
{
    return require "core/Helpers/access.php";
}

function public_folder(): string
{
    $scriptFolder = script_folder();

    return "/$scriptFolder/public";
}

function script_folder(): string
{
    return Container::get('config')->script_folder ?? '';
}

function twig(string $name, $param = [])
{
    /* @var \Nikacrm\Core\TwigTemplater $twigContainer */
    $twigContainer = Container::get('twig');
    $twig          = $twigContainer->instance();

    $name = str_replace('.', '/', $name);

    echo $twig->render("{$name}.html.twig", $param);
}

function render_twig(string $name, $param = [])
{
    /* @var \Nikacrm\Core\TwigTemplater $twigContainer */
    $twigContainer = Container::get('twig');
    $twig          = $twigContainer->instance();

    $name = str_replace('.', '/', $name);

    return $twig->render("{$name}.html.twig", $param);
}

/**
 * Переводит строку. Если нет перевода в файле translation.php, то используется сама строка
 * @param  string  $string
 * @return void
 */
function t(string $string): string
{
    return Container::get('translate')->$string ?? $string;
}

/**
 * Рандомная задержка от нуля до заданной величины
 * @param  int  $seconds
 * @return void
 */
function rand_delay(int $seconds = 3)
{
    $microseconds = $seconds * 1000000;
    try {
        $randMs = random_int(0, $microseconds);
    } catch (Exception $e) {
    }
    usleep($randMs);
}

/** Получаем по ключу набор данных в виде ключ-значение
 * @param  string  $string
 * @return string
 */
function enum(string $string): string
{
    return Container::get('translate')->$string ?? $string;
}

/**
 * Redirect to a new page.
 *
 * @param  string  $path
 */
function redirect(string $path, $returnPage = '')
{
    $baseUrl = DIRECTORY_SEPARATOR.Container::get('config')
        ->script_folder.DIRECTORY_SEPARATOR;
    if ($returnPage) {
        $returnPage = '?return_page='.$returnPage;
    }
    header("Location: {$baseUrl}{$path}{$returnPage}");
    exit();
}

function hash_password(string $password)
{
    return sha1($password);
}

/**
 * Обертка для json_encode
 * @param $data
 * @return false|string|void
 */
function je($data)
{
    try {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    } catch (JsonException $e) {
        //TODO helper exception
    }
}

/**
 * Обертка для json_decode
 * @param $data
 * @return false|string|void
 */
function jd($data)
{
    try {
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        //TODO helper exception
    }
}

/**
 * Конвертим объект в массив https://stackoverflow.com/a/16023589/7229734
 * @param $object
 * @return array
 */
function dismount($object): array
{
    $reflectionClass = new ReflectionClass(get_class($object));
    $array           = [];
    foreach ($reflectionClass->getProperties() as $property) {
        $property->setAccessible(true);
        $array[$property->getName()] = $property->getValue($object);
        $property->setAccessible(false);
    }

    return $array;
}

/**
 * @return string
 */
function memory_peak(): string
{
    return round(memory_get_peak_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
}

/* Peak memory usage */

function memory_usage(): string
{
    return round(memory_get_usage() / 1024 / 1024, 2).' MB'.PHP_EOL;
}


function print_error(AmoCRMApiException $e): void
{
    $errfile = 'unknown file';
    $errstr  = 'shutdown';
    $errno   = E_CORE_ERROR;
    $errline = 0;

    $errorTitle = $e->getTitle();
    $code       = $e->getCode();
    $debugInfo  = var_export($e->getLastRequestInfo(), true);

    $error = <<<EOF
Error: $errorTitle
Code: $code
Debug: $debugInfo
EOF;

    echo '<pre>'.$error.'</pre>';
}

/**
 * @param $array
 * @return array
 */
function get_array($array): array
{
    return (isset($array) && is_array($array)) ? $array : [];
}


function get_class_name($object): ?string
{
    try {
        return (new \ReflectionClass($object))->getShortName();
    } catch (\ReflectionException $e) {
    }
}


/**
 * @return bool
 */
function is_local_host(): bool
{
    return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
}

function format_exception_message(Throwable $e)
{
    $trace      = get_exception_trace($e);
    $errorTitle = $e->getMessage();
    $file       = $e->getFile();
    $line       = $e->getLine();
    $code       = E_CORE_ERROR;

    $message = "💔Error: $errorTitle".PHP_EOL."📁File: $file at $line".PHP_EOL."💥Debug: $trace";

    return $message;
}

function get_exception_trace($exception)
{
    $rtn   = '';
    $count = 0;
    foreach ($exception->getTrace() as $frame) {
        $args = '';
        if (isset($frame['args'])) {
            $args = [];
            foreach ($frame['args'] as $arg) {
                if (is_string($arg)) {
                    $args[] = "'".$arg."'";
                } elseif (is_array($arg)) {
                    $args[] = 'Array';
                } elseif (is_null($arg)) {
                    $args[] = 'NULL';
                } elseif (is_bool($arg)) {
                    $args[] = ($arg) ? 'true' : 'false';
                } elseif (is_object($arg)) {
                    $args[] = get_class($arg);
                } elseif (is_resource($arg)) {
                    $args[] = get_resource_type($arg);
                } else {
                    $args[] = $arg;
                }
            }
            $args = join(', ', $args);
        }
        $rtn .= sprintf("#%s %s(%s): %s(%s)\n",
          $count,
          isset($frame['file']) ? $frame['file'] : 'unknown file',
          isset($frame['line']) ? $frame['line'] : 'unknown line',
          (isset($frame['class'])) ? $frame['class'].$frame['type'].$frame['function'] : $frame['function'],
          $args);
        $count++;
    }

    return $rtn;
}

function base_url()
{
    return 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
        ->script_folder.DIRECTORY_SEPARATOR;
}

function main_page()
{
    return 'app/';
}