<?php

namespace Nikacrm\Core;

use Nikacrm\Core\Base\Controller;
use Throwable;

class Router
{

    private const ROUTES_CONFIG_PATH = 'config/routes.php';

    /**
     * All registered routes.
     *
     * @var array
     */
    public array $routes = [
      'GET'  => [],
      'POST' => [],
    ];

    /**
     * Load a user's routes file.
     *
     * @param  string  $file
     * @return \Nikacrm\Core\Router
     */
    public static function boot(string $file = self::ROUTES_CONFIG_PATH)
    {
        $router = new static;
        require $file;

        return $router;
    }

    /**
     * Load the requested URI's associated controller method.
     *
     * @param  string  $uri
     * @param  string  $requestMethod
     * @return mixed
     * @throws \Exception
     */
    public function direct(string $uri, string $requestMethod)
    {
        $this->prepareUri($uri);
        if (array_key_exists($uri, $this->routes[$requestMethod])) {
            $requestType  = $this->routes[$requestMethod][$uri]['type'] ?? 'url';
            $requestRoles = $this->routes[$requestMethod][$uri]['roles'];

            if (!empty($requestRoles)) {
                /* @var \Nikacrm\Core\Access $access */
                $access = Container::get('access');
                $access->checkAuth($requestRoles, $uri);
            }
            $requestAction = $this->routes[$requestMethod][$uri]['action'];
            Container::bind('request', Request::process($requestType)->getData());//respond_and_proceed

            return $this->callAction($this->routes[$requestMethod][$uri]['controller'], $requestAction);
        }
        Logger::start(['channel_name' => '404'])->save("{$uri} {$requestMethod}", 'error');
        view('system/404');
    }

    /**
     * Register a GET route.
     *
     * @param  string  $uri
     * @param  string  $controller
     * @param  string  $action
     * @param  array  $params
     */
    public function get(string $uri, string $controller, string $action, array $params = [])
    {
        $this->routes['GET'][$uri]['controller'] = $controller;
        $this->routes['GET'][$uri]['action']     = $action;
        $this->routes['GET'][$uri]['type']       = $params['type'] ?? 'url';
        $this->routes['GET'][$uri]['roles']      = $params['roles'] ?? [];
    }

    /**
     * Register a POST route.
     *
     * @param  string  $uri
     * @param  string  $controller
     * @param  string  $action
     * @param  array  $params
     */
    public function post(string $uri, string $controller, string $action, array $params = [])
    {
        $this->routes['POST'][$uri]['controller'] = $controller;
        $this->routes['POST'][$uri]['action']     = $action;
        $this->routes['POST'][$uri]['type']       = $params['type'] ?? 'url';
        $this->routes['POST'][$uri]['roles']      = $params['roles'] ?? [];
    }

    /**
     * Проверяем, есть ли в конфиге имя папки, в которой может находится скрипт. Если есть - то добавляем, чтобы
     * роутер мог понять путь
     * @param $uri
     */
    private function prepareUri(&$uri): void
    {
        $config       = Container::get('config');
        $scriptFolder = rtrim($config->script_folder, '/');
        if ($scriptFolder) {
            if ($scriptFolder === $uri) {
                $uri = '';
            } else {
                $uri = str_replace($scriptFolder.'/', '', $uri);
            }
        }
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param  string  $controller
     * @param  string  $action
     * @return mixed|void
     */
    protected function callAction(string $controller, string $action)
    {
        //$controller = "Nikacrm\\App\\Controllers\\{$controller}";
        $controller = new $controller;
        try {
            if (!method_exists($controller, $action)) {
                throw new \Exception(
                  "{$controller} does not respond to the {$action} action."
                );
            }

            return $controller->$action();
        } catch (Throwable $t) {
            $exceptionMessage = format_exception_message($t);
            Logger::start(['channel_name' => 'exceptions'])->save($exceptionMessage, 'error');
        }
    }
}