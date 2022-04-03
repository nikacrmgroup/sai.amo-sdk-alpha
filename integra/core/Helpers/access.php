<?php
//put sha1() encrypted password here - example is 'hello'
use Nikacrm\Core\Container;

$password = mb_strtolower(Container::get('config')->pages_password);
//phpinfo();
//session_start();
if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}

if (isset($_POST['password'])) {
    if (sha1($_POST['password']) == $password) {
        $_SESSION['loggedIn'] = true;
    } else {
        view('system/403');
        die ();
    }
}

if (!$_SESSION['loggedIn']):
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Авторизация</title>
        <link href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css' rel='stylesheet'>
    </head>
    <body class='flex flex-col items-center justify-center w-screen h-screen bg-gray-200 text-gray-700'>

    <!-- Component Start -->
    <h1 class='font-bold text-2xl'>Для просмотра страницы необходима авторизация :)</h1>
    <form class='flex flex-col bg-white rounded shadow-lg p-12 mt-12' method="post">
        <input type="hidden" name="auth" value="form">
        <label class='font-semibold text-xs mt-3' for='passwordField'>Пароль</label>
        <input class='flex items-center h-12 px-4 w-64 bg-gray-200 mt-2 rounded focus:outline-none focus:ring-2'
               type='password' name='password'>
        <button type='submit' name='submit' class='flex items-center justify-center h-12 px-6 w-64 bg-blue-600 mt-8
        rounded font-semibold text-sm text-blue-100 hover:bg-blue-700'>
            Войти
        </button>
    </form>
    <!-- Component End  -->

    </body>
    </html>

    <?php
    exit();
endif;
?>