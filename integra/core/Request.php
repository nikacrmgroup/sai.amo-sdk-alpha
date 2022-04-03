<?php
/** @noinspection JsonEncodingApiUsageInspection */

namespace Nikacrm\Core;


class Request
{

    private const REQUEST_FINISH_CODE    = 204;
    private const REQUEST_FINISH_MESSAGE = 'The connection is closed and the process is continued';
    private $config;
    private string $debugRequestPath = __DIR__.'/../debug/';

    private $logger;
    private $requestData;
    private $type;

    private function __construct($type = 'url')
    {
        $this->type   = $type;
        $this->config = Container::get('config')->getAll();
        $this->logger = Container::get('request_logger');

        $this->setEnv();
        if ($type === 'respond_and_proceed') {
            $this->sendResponseAndProceed();
        } elseif ($type === 'close_connection_and_proceed') {
            $this->closeConnectionAndProceed();
        } else {
            $this->sendHeaders();
        }

        if ($this->config['dummy_request']) {
            $this->requestData = $this->debugAmoRequest();
        } else {
            $this->requestData = $this->getRequest();
        }

        /*   if ($type !== 'inner' && $type !== 'webhook') {
               $this->checkOrigin();
               $this->_checkToken();
           }*/
        $server = $_SERVER;
        /*дампим сервер, но без "длинных" параметров*/
        unset($server['PATH'], $server['PATHEXT']);

        $this->logger->save('☢ Request => '.je($this->requestData).' 🐍 SERVER => '.je($server));
    }


    public function getData()
    {
        if ($this->type === 'inner') {
            /*Превращаем значения с формы, переданные через POST в нормальные, через пробелы*/
            return $this->prepare($this->requestData);
        }

        return $this->requestData;
    }

    public function getSessionData()
    {
        return $this->requestData;
    }

    public function getWebHookData()
    {
        return $this->requestData;
    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function process($type = 'url'): Request
    {
        return new Request($type);
    }

    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri(): string
    {
        return trim(
          parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
          '/'
        );
    }

    private function checkOrigin(): void
    {
        $origin = '';
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = parse_url($_SERVER['HTTP_ORIGIN'], PHP_URL_HOST);
        } elseif (isset($_SERVER['HTTP_REFERER'])) {
            $origin = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        }

        $allowed_domains = $this->config['allowed_domains'];
        /*Если доменное имя входит в список разрешенных, или там стоит звездочка *(т.е. "все"), то разрешаем*/
        if (in_array($origin, $allowed_domains, true)) {
            header('Access-Control-Allow-Origin: '.$origin);
        } elseif (in_array('*', $allowed_domains, true)) {
            header('Access-Control-Allow-Origin: *');
        } else {
            header('HTTP/1.0 403 Forbidden');
            exit;
        }
    }

    /**
     * @return bool
     */
    private function checkToken()
    {
        if (@$this->requestData['POST']['token'] !== $this->config['token']) {
            header('HTTP/1.0 403 Forbidden');
            exit;
        }

        return true;
    }

    private function debugAmoRequest()
    {
        //todo название файла или в гет или в конфиг
        $debugRequestFileJson = file_get_contents($this->debugRequestPath.Container::get('config')->dummy_file_name);

        return json_decode($debugRequestFileJson, true);
    }

    private function getPost()
    {
        if (!empty($_POST)) {
            // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
            // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
            return $_POST;
        }

        // when using application/json as the HTTP Content-Type in the request
        $post = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $post ?? [];
        }

        return [];
    }

    /**
     * @return mixed
     */
    private function getRequest(): array
    {
        $post    = ['POST' => $this->getPost()];
        $get     = ['GET' => $_GET ?? []];
        $session = ['SESSION' => $_SESSION ?? []];


        return array_merge($post, $get, $session); //сохраняем запрос для выбора параметров
    }

    private function prepare($data)
    {
        if (isset($data['POST'])) {
            $postData     = $data['POST'];
            $keys         = str_replace(['_', '*#*', '*dot*'], [' ', '_', '.'], array_keys($postData));
            $data['POST'] = array_combine($keys, array_values($postData));
        }

        return $data;
    }

    private function sendHeaders()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization');
        //header("HTTP/1.1 200 OK");

    }

    private function closeConnectionAndProceed(): void
    {
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            ob_end_clean();

            ignore_user_abort(true); // optional
            ob_start();

            echo(str_repeat(' ', 75537)); // [+] Line added: Fill up mod_fcgi's buffer.


            $size = ob_get_length();
            header('Connection: close');
            header('Content-Encoding: none');
            header("Content-Length: $size");
            ob_end_flush();     // Strange behaviour, will not work
            flush();            // Unless both are called !
            //ob_end_clean();
            // close current session
            if (session_id()) {
                session_write_close();
            }
        }
        /*sleep(25);
        $log->save('Ну что подождали...');*/
    }

    /**
     *  Отправляем сразу закрытие соединения с OK чтобы, например, xhr не ждал ответа
     */
    private function sendResponseAndProceed(): void
    {
        if (function_exists('fastcgi_finish_request')) {
            //header('Content-type: application/json; charset=utf-8');
            echo je([
              'status'     => self::REQUEST_FINISH_CODE,
              'message'    => self::REQUEST_FINISH_MESSAGE,
              'process_id' => getmypid(),
            ]);
            fastcgi_finish_request();
        } else {
            ob_end_clean();

            ignore_user_abort(true); // optional
            ob_start();
            //header('Content-type: application/json; charset=utf-8');

            echo je([
              'status'     => self::REQUEST_FINISH_CODE,
              'message'    => self::REQUEST_FINISH_MESSAGE,
              'process_id' => getmypid(),

            ]);
            echo(str_repeat(' ', 75537)); // [+] Line added: Fill up mod_fcgi's buffer.


            $size = ob_get_length();
            header('Connection: close');
            header('Content-Encoding: none');
            header("Content-Length: $size");
            ob_end_flush();     // Strange behaviour, will not work
            flush();            // Unless both are called !
            //ob_end_clean();
            // close current session
            if (session_id()) {
                session_write_close();
            }
        }
        /*sleep(25);
        $log->save('Ну что подождали...');*/
    }

    /**
     * Настраиваем дебаг окружение
     */
    private function setEnv(): void
    {
        ini_set('log_errors', 1);
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        ini_set('error_log', 'php-error.log');
    }

}