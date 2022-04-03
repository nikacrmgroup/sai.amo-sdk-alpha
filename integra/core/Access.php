<?php

namespace Nikacrm\Core;

use Nikacrm\Core\DTO\AuthDTO;

final class Access
{

    protected const USERS_FILE = 'users.php';
    protected const ROLES_FILE = 'roles.php';
    private string $clientId;

    /* @var \Nikacrm\Core\Config $config */
    private $config;
    private string $configPath = __DIR__.'/../config/';

    private $db;
    /**
     * @var \Nikacrm\Core\Logger
     */
    private Logger $logger;

    public array $roles;

    /* @var \Nikacrm\Core\Session $session
     */
    private $session;

    public array $users;

    private function __construct()
    {
        $this->logger = Container::get('auth_logger');
        $this->db     = Container::get('database');
        $this->users  = $this->getFile(self::USERS_FILE);
        $this->roles  = $this->getFile(self::ROLES_FILE);
        /* @var \Nikacrm\Core\Config $config */
        $this->config   = Container::get('config');
        $this->clientId = $this->config->client_id;
        $this->session  = Container::get('session');
    }

    public function authOrLogin()
    {
        $session = $this->session;

        if ($user = $session->getLoggedInUser()) {
            //todo
            if ($this->hasFormSubmitted()) {
                $this->renderSuccessPage($user);
            } else {
                view('system/success_login', [
                  'message' => 'Вы уже авторизованы', 'logo' => '👲🏻', 'returnPage' =>
                    $this->getReturnPage(),
                ]);
                exit();
            }
        }

        $clientId = $this->clientId;
        $loggedIn = false;

        if (isset($_POST['password'], $_POST['login'])) {
            if (!Session::checkCsrf()) {
                view('system/403_csrf');
                die ();
            }


            $login    = $_POST['login'];
            $password = $_POST['password'];

            $user = $this->getUserConfig($login);

            if (!$user || !$this->passwordValid($user, $password)) {
                $this->renderLoginForm(['message' => t('login_error')]);
            } else {
                $authDto = new AuthDTO;
                $authDto->setLogin($login)
                        ->setPassword($password)
                        ->setClientId($clientId);


                $session->saveAuth($authDto);


                $loggedIn = true;

                $message = $user['name'].', '.t('login_success');
                view('system/success_login',
                  ['message' => $message, 'logo' => '👲🏻', 'returnPage' => $this->getReturnPage()]);
            }
        }

        if (!$loggedIn) {
            $this->renderLoginForm();
        }

        return $loggedIn;
    }

    public function checkAuth($roles = [], $returnPage = 'app')
    {
        $session = $this->session;
        $user    = $session->getLoggedInUser();
        if (!$user) {
            redirect('login', $returnPage);
        }
        if (!empty($roles)) {
            /*если есть проверка на роли*/
            $intersect = array_intersect($roles, $user['roles']);
            if (count($intersect) === 0) {
                view('system/403');
                exit();
            }
        }
    }

    public function getLoggedInUser()
    {
        $session = $this->session;
        $user    = $session->getLoggedInUser();

        return $user;
    }

    public function getReturnPage()
    {
        $returnPage = 'app';

        if (isset($_REQUEST['return_page']) && $_REQUEST['return_page'] !== 'login') {
            $returnPage = $_REQUEST['return_page'];
        }

        return $returnPage;
    }

    public function getUserConfig($login): array
    {
        $result = [];
        foreach ($this->users as $user) {
            if ($user['login'] === $login) {
                $result = $user;

                break;
            }
        }

        return $result;
    }

    public function logout()
    {
        $session = $this->session;
        $user    = $session->getLoggedInUser();
        if ($user) {
            $session->clearUserAuthSession($user['login']);
        }
        redirect('app');
    }

    public static function prepare()
    {
        $access = new self();
        Container::bind('access', $access);

        return $access;
    }

    public function roles()
    {
        return $this->roles;
    }

    public function users()
    {
        return $this->users;
    }

    private function getFile($fileName)
    {
        $file = $this->configPath.$fileName;

        return include($file);
    }

    private function hasFormSubmitted($type = 'login')
    {
        return isset($_POST['form']) && $_POST['form'] === $type;
    }

    private function passwordValid($user, $password): bool
    {
        $sha = hash_password($password);

        return $sha == $user['password'];
    }

    private function renderLoginForm($params = [])
    {
        view('login', $params);
        exit();
    }

    private function renderSuccessPage($user)
    {
        $login    = $_SESSION[$this->clientId]['login'];
        $userName = $user['name'];
        /*Проверяем - отправлена форма, или нет. Если нет, то просто пишем, что уже авторизованы*/
        if (isset($_POST['form']) && $_POST['form'] === 'login') {
            view('system/success_login', ['message' => 'Logged', 'logo' => '👲🏻']);
            exit();
        }

        $message = $userName.', '.t('login_success');
        view('system/success_login', ['message' => $message, 'logo' => '👲🏻', 'returnPage' => $this->getReturnPage()]);
        exit();
    }


}