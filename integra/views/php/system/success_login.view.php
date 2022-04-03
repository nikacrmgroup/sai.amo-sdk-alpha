<?php

/*http_response_code(204);*/

/*$mainPage = main_page();
header("Refresh: 10; url=$mainPage");*/

use Nikacrm\Core\Container;

$page        = $returnPage ?? main_page();
$refreshTime = Container::get('config')->refresh_timeout;
header("Location: $page");
//header("Refresh: $refreshTime; url=$page");

$message = $param['message'] ?? '✅';
view('partials/header', ['title' => $message]);
?>
    <div class='bg-white dark:bg-gray-800 flex relative z-20 items-center overflow-hidden'>
        <div class='container mx-auto px-6 flex relative py-16'>
            <div class='sm:w-2/3 lg:w-2/5 flex flex-col relative z-20'>
            <span class='w-20 h-2 bg-gray-800 dark:bg-white mb-12'>
            </span>
                <h1 class='font-bebas-neue uppercase text-5xl sm:text-5xl font-black flex flex-col leading-none
                dark:text-white text-gray-800'>
                    Вход
                    <span class='text-4xl sm:text-4xl'>
                    в аккаунт
                </span>
                </h1>
                <p class='mt-10 text-2xl text-gray-700 dark:text-white'>
                    <?= $message ?>
                </p>
                <p class='mt-5 text-1xl text-blue-500 dark:text-white'>
                    Страница будет автоматически перенаправлена на <?= $page ?> через <?= $refreshTime ?> секунд
                </p>
                <div class='flex mt-8'>
                    <a href='<?= main_page() ?>'
                       class='uppercase py-2 px-4 rounded-lg bg-blue-500 border-2 border-transparent text-white
                       text-md mr-4 hover:bg-blue-400'>
                        Вернуться
                    </a>
                    <a href='<?= base_url() ?>logout'
                       class='uppercase py-2 px-4 rounded-lg bg-transparent border-2 border-pink-500 text-pink-500 dark:text-white hover:bg-pink-500 hover:text-white text-md'>
                        🚪 Выйти из аккаунта
                    </a>
                </div>
            </div>
            <div class='sm:w-2/3 lg:w-2/5 flex flex-col self-center  items-center'>

                <div class="mx-auto max-w-7xl h-auto text-8xl  ">
                    <?= $logo ?? '200' ?>

                </div>
            </div>
        </div>
    </div>
<?php
view('partials/footer');

?>