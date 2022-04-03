<?php

namespace Nikacrm\App\Controllers;

use Nikacrm\Core\Base\Controller;
use Nikacrm\Core\Container;

class PagesController extends Controller
{


    /**
     * Show the home page.
     */
    public function home()
    {
        return view('index');
    }

    public function test200()
    {
        sleep(10);
        echo je(['delayed' => 200]);
        $logger = Container::get('request_logger');
        $logger->save('🐢🐢🐢 200 tested');
    }

    public function login()
    {
        /* @var \Nikacrm\Core\Access $access */
        $access = Container::get('access');
        $access->authOrLogin();
        //return view('login');
    }

    public function logout()
    {
        /* @var \Nikacrm\Core\Access $access */
        $access = Container::get('access');
        $access->logout();
    }

    /**
     * Show the readme page.
     */
    public function readme()
    {
        /* @var \Nikacrm\Core\Access $access */
        /*      $access = Container::get('access');

              $access->checkAuth(['admin'], 'readme');*/

        return view('readme');
    }
}