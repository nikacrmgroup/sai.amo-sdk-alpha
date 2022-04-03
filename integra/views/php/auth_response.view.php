<?php

use Nikacrm\Core\Container;

require('partials/head.php'); ?>
    <div class='px-4 py-16 mx-auto sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8 lg:py-20'>
        <div class='p-8 rounded shadow-xl sm:p-16'>
            <div class='flex flex-col lg:flex-row'>
                <div class='mb-6 lg:mb-0 lg:w-1/2 lg:pr-5'>
                    <h2 class='font-sans text-3xl leading-6 font-bold tracking-tight text-gray-900 sm:text-4xl
                    sm:leading-none'>
                        Авторизация интеграции<br class='hidden md:block'/>прошла <span
                                class='inline-block text-green-400'>успешно</span>, <br class='hidden md:block'/>
                        <span class='inline-block text-purple-700'><?= $owner ?></span>!
                    </h2>
                </div>
                <div class='lg:w-1/2'>
                    <p class='mb-4 text-base text-gray-700'>
                        Был создан Refresh токен. При обращениях к амо интеграция через api обновляет его
                        автоматически. После
                        последнего обновления токен действует всего <b>3 месяца</b>. Если интеграция не
                        используется в течение 3 месяцев, не было ни одного запроса на актуализацию ключа, то интеграция
                        потеряет доступ к данным и будем необходимо повторно запрашивать разрешение у пользователя на
                        доступ к его аккаунту. /документация amoCRM
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php
require('partials/footer.php'); ?>