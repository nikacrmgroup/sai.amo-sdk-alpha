<?php

namespace Nikacrm\Core;

use Nikacrm\Core\DTO\AuthDTO;

class Session
{

    private string $clientId;
    /**
     * @var \Nikacrm\Core\Config $config
     */
    private $config;

    /**
     * @var \Nikacrm\Core\Logger $logger
     */
    private $logger;

    private function __construct()
    {
        $this->config   = Container::get('config');
        $this->clientId = $this->config->client_id;
        $this->logger   = Container::get('auth_logger');
    }

    /**
     * @return bool
     */
    public static function checkCsrf(): bool
    {
        if (!empty($_POST['csrf'])) {
            return hash_equals($_SESSION['csrf'], $_POST['csrf']);
        }

        return false;
    }

    public function clearAllAuthSession(): void
    {
        //todo
        unset($_SESSION[$this->clientId]['auth']);
        $this->logger->save('🧼 Все сессии авторизации очищены!');
    }

    public function clearAllSession(): void
    {
        //todo
        session_destroy();
        $this->logger->save('🧼 Все сессии очищены!');
    }

    public function clearUserAuthSession(string $login)
    {
        unset($_SESSION[$this->clientId]['auth'][$login]);
        $this->logger->save("🧼 Сессии авторизации для {$login} очищена!");
        redirect(main_page());
    }

    /**
     * https://stackoverflow.com/a/31683058/7229734
     * @return void
     */
    public static function generateCsrf()
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
    }

    public function get(string $name)
    {
        return $_SESSION[$this->clientId][$name] ?? [];
    }

    public function getLoggedInUser()
    {
        $clientId   = $this->clientId;
        $authCookie = $this->getAuthCookie();
        if ($authCookie) {
            [$login, $hash] = explode('_', $authCookie);
            if (isset($_SESSION[$clientId]['auth'][$login])) {
                $user = $this->getUserByCookie($authCookie);

                return $user;
            }
        }

        return [];
    }

    public static function prepare()
    {
        Container::bind('session', new self());
    }

    public function save(string $name, $params = [])
    {
        $_SESSION[$this->clientId][$name] = $params;
    }

    public function saveAuth(AuthDTO $authDto)
    {
        $login = $authDto->getLogin();

        $_SESSION[$this->clientId]['auth'][$login]['password'] = $authDto->getPassword();

        $this->saveAuthCookie($authDto);
    }

    private function getAuthCookie()
    {
        $cookieName = 'auth_'.$this->clientId;

        $authCookie = $_COOKIE[$cookieName] ?? '';


        return $authCookie;
    }

    private function getUserByCookie($authCookie)
    {
        if ($authCookie && str_contains($authCookie, '_')) {
            $clientId = $this->clientId;


            /*Получаем логи и хеш для сверки*/
            $explodedCookie = explode('_', $authCookie);
            [$login, $md5Hash] = $explodedCookie;
            //TODO singleton
            $user = (Access::prepare())->getUserConfig($login);

            if ($user) {
                $password         = $user['password'];
                $generatedMd5Hash = md5($clientId.$login.$password);

                if ($md5Hash === $generatedMd5Hash) {
                    return $user;
                }
            }
        }

        return [];
    }

    private function saveAuthCookie(AuthDTO $authDto)
    {
        $cookieData = "{$authDto->getLogin()}_{$authDto->getMd5string()}";
        setcookie('auth_'.$authDto->getClientId(), $cookieData);
    }


}