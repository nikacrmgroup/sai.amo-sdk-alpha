<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport'
          content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Авторизация</title>
    <link href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css' rel='stylesheet'>
</head>
<body class='flex flex-col items-center justify-center w-screen h-screen bg-gray-200 text-gray-700'>

<!-- Component Start -->
<h1 class='font-bold text-2xl'>Необходима авторизация</h1>
<form class='flex flex-col bg-white rounded shadow-lg p-12 mt-12' method='post'>
    <input type='hidden' name='form' value='login'>
    <input type='hidden' name='csrf' value='<?= @$_SESSION['csrf'] ?>'>
    <label class='font-semibold text-xs mt-3' for='passwordField'>Логин</label>
    <input required class='flex items-center h-12 px-4 w-64 bg-gray-200 mt-2 rounded focus:outline-none focus:ring-2'
           type='text' name='login'>
    <label class='font-semibold text-xs mt-3' for='passwordField'>Пароль</label>
    <input required class='flex items-center h-12 px-4 w-64 bg-gray-200 mt-2 rounded focus:outline-none focus:ring-2'
           type='password' name='password'>
    <button type='submit' name='submit' class='flex items-center justify-center h-12 px-6 w-64 bg-blue-600 mt-8
        rounded font-semibold text-sm text-blue-100 hover:bg-blue-700'>
        Войти
    </button>
    <?php
    if (isset($message)) : ?>
        <div class="text-s items-center bg-orange mt-2"><?= $message ?></div>
    <?php
    endif; ?>
</form>
<!-- Component End  -->

</body>
</html>