<?php

use Nikacrm\Core\Container;

$config        = Container::get('config');
$redirectUri   = Container::get('config')->redirect_uri;
$baseScriptUrl = 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
    ->script_folder.DIRECTORY_SEPARATOR;


?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css' rel='stylesheet'>
    <title>🗿 Инструкции и инструменты скрипта</title>
</head>
<body>
<div class='bg-lightblue py-20 px-4'>
    <div class='mx-auto max-w-7xl flex flex-col md:flex-row'>
        <h2 class='mr-8 w-full md:w-1/5 text-3xl font-extrabold leading-9'>
            🗿 Инструкции и инструменты скрипта
        </h2>
        <dl class='w-full md:w-4/5'>
            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    🧙🏻‍♂️🧬 Как устроена логика скрипта?
                </h3>
            </dt>
            <dd class='mb-16'>


                <p class='mb-3'>При создании заказа в ЛК сайта программно шлется POST запрос с информацией заказа. По
                    нему в амо
                    создаются Сделка у контакта Партнера в определенной воронке/этапе с заполнением полей заказа, а
                    также создание Примечания с данными заказа, которые являются расширенной информацией. В сделку
                    крепятся товары из заказа. </p>

                <p class='mb-3'>Поиск контакта Партнера в амо осуществляется по полю, в котором содержится id Партнера -
                    в запросе
                    это, например, 'customer_id': '1',. Для работы интеграции данное поле уже должно быть заполнено у
                    каждого контакта Партнера</p>
                <p class='mb-3'>Данные покупателя идут в сделку в соответствующие поля, которые определены в конфиге
                    полей</p>


                <p class='mb-3'><a target='_blank' class='text-blue-600 hover:text-blue-700'
                                   href='https://docs.google.com/document/d/1koYJrsI17NKL4cVgST-OD6gx2wzRGCKP6n61a3X9IG0/edit#'>Подробнее
                        в ТЗ</a></p>


            </dd>
            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    🛠 Установка скрипта
                </h3>
            </dt>
            <dd class='mb-16'>
                <p>
                <ul class='list-disc space-y-2'>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Конфигурационные файлы для рабочих окружений находятся в папках внутри /config. Выбор
                            конкретного окружения задается в файле .env.type в корне папки /config. Например, если
                            это рабочее окружение, то в файл пишем prod и все конфиги приложения будут в папке prod.
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Создать базу данных на хостинге. Сделать импорт базы из файла в папке /db, которая лежит
                            в корне скрипта. Прописать данные доступа к БД в файле конфига в параметре
                            <code class='text-sm font-bold text-gray-900'>database</code>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Если скрипт расположен не в корне домена, то нужно задать папку в 2-х местах: в конфиге
                            параметр <code class='text-sm font-bold text-gray-900'>script_folder</code> и в файле
                            .htaccess в правилах, например так: <code class='text-sm font-bold
                            text-gray-900'>RewriteRule ^.*$ /название_папки/index.php [L,QSA]</code>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            В конфиге скрипта записать урл редиректа: <code
                                    class='text-sm font-bold text-gray-900'><?= $baseScriptUrl ?>api/auth</code>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Создать интеграцию в амо <a target='_blank' class='text-blue-600 hover:text-blue-700'
                                                        href='https://i.imgur.com/rUAauaH.jpg'>https://i.imgur.com/rUAauaH.jpg</a>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Заполнить редирект урл(тот который в конфиге) в параметре: <code
                                    class='text-sm font-bold text-gray-900'>redirect_uri</code>

                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Сохранить или сразу записать в файл конфига <code
                                    class='text-sm font-bold
                                    text-gray-900'>папка_скрипта/config/название_окружения/amo.config.php</code>
                            эти значения <a target='_blank' class='text-blue-600 hover:text-blue-700'
                                            href='https://i.imgur.com/YFj9l6w.jpg'>https://i.imgur.com/YFj9l6w.jpg</a>,
                            где домен - это домен
                            вашего аккаунт, редирект урл - это все тот же <code
                                    class='text-sm font-bold text-gray-900'>redirect_uri</code>,
                            а ключ и id клиента
                            - в интеграции амо <a target='_blank' class='text-blue-600 hover:text-blue-700'
                                                  href='https://i.imgur.com/FIhoKEa.jpg'>https://i.imgur.com/FIhoKEa.jpg</a>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-blue-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            После заполнения конфига, перейти по адресу <a target='_blank'
                                                                           class='text-blue-600 hover:text-blue-700'
                                                                           href='<?= $redirectUri ?>'><?= $redirectUri ?></a>
                            - всплывет кнопка, подтвердить доступ, затем должна быть надпись про успех авторизации.
                        </p>
                    </li>

                </ul>

            </dd>

            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    🛠 Настройки скрипта
                </h3>
            </dt>
            <dd class='mb-16'>
                <p>
                <ul class='list-disc space-y-2'>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-purple-400' viewBox='0 0 20 20'
                                   fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Пароли и логины пользователей задается в конфиге <b>/config/user.php</b> https://i.imgur
                            .com/oKzdqvx.png . Для создания пользователя, нужно "скопировать" массив, и указать login
                            - это то, что в форме авторизации вводить, пароль password - это хеш пароля, который
                            вводят в форме, его можно сформировать в сервисе
                            <a target='_blank'
                               class='text-blue-600 hover:text-blue-700'
                               href='https://passwordsgenerator.net/sha1-hash-generator/'>https://passwordsgenerator
                                .net/sha1-hash-generator/</a> . По-умолчанию, это логин, т.е. для admin пароль admin.
                            И задать роль - их список можно посмотреть в файле <b>/config/roles.php</b>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-purple-400' viewBox='0 0 20 20'
                                   fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            <code class='text-sm font-bold text-gray-900'>cache</code> параметр задает время жизни
                            кеша по ключам, например для контактов установлены сутки 24 * 60 * 60
                        </p>
                    </li>

                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-purple-400' viewBox='0 0 20 20'
                                   fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            <code class='text-sm font-bold text-gray-900'>domain, client_id, client_secret,
                                redirect_uri</code> - параметры авторизации
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-purple-400' viewBox='0 0 20 20'
                                   fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            В файле <b>amo.config.php</b> можно задать <code class='text-sm font-bold
                            text-gray-900'>contact_cf_partner_id</code> - id поля контакта, по которому ищем партнера
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-purple-400' viewBox='0 0 20 20'
                                   fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Соответствие полей запроса и амо, а также настройки именования сделки и куда она
                            попадает, задается в файле <b>request.mapper.php</b>. Тут можно задать воронку, этап куда
                            сделка создается, а также имя для новой сделки: <br>
                            <code
                                    class='text-sm font-bold
                            text-gray-900'>'pipeline_id' => 1731979,
                                'status_id' => 26034988,
                                'lead_name' => 'Заказ из ЛК',</code> <br>
                            В разделе <code
                                    class='text-sm font-bold
                            text-gray-900'>mapping</code> задается соответствие полей в запросе, с маппингом полей
                            амо. Сам маппинг амо задается в другом конфиге: <b>amo-fields.mapper.php</b>. Чтобы
                            задать, находим id поля в амо, пишем в маппинг, где для поля сами придумываем машинное
                            имя, которое будет использовано в файле <b>request.mapper.php</b>. Например, в запросе
                            есть поле <i>payment_address_1</i> - находим в амо его id, создаем запись в <b>amo-fields
                                .mapper.php</b> в виде : <code
                                    class='text-sm font-bold
                            text-gray-900'>'lead.cf.customer_payment_address_1' => [
                                'id' => 685733,
                                ],</code> и затем в файле <b>request.mapper.php</b> прописываем соответствие <code
                                    class='text-sm font-bold
                            text-gray-900'>'payment_address_1' => [
                                'id' => 'payment_address_1',
                                'mapped_id' => 'lead.cf.customer_payment_address_1',

                                ],</code> . Иззи) Теперь поле запроса пишется в поле сделки.
                            >
                        </p>
                    </li>


                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-purple-400' viewBox='0 0 20 20'
                                   fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Остальные, не описанные выше парамеры являются системными для скрипта.
                        </p>
                    </li>
                </ul>

            </dd>

            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    👑 Администрирование
                </h3>
            </dt>
            <dd class='mb-16'>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
                         ->script_folder.DIRECTORY_SEPARATOR.'app/transactions'
                       ?>">📃 Просмотр логов транзакций приложения
                    </a>
                </p>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= $redirectUri ?>">Обновить авторизацию
                    </a>
                </p>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
                         ->script_folder.DIRECTORY_SEPARATOR.'api/cache/clear'
                       ?>">Сброс всех кешей
                    </a>
                </p>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
                         ->script_folder.DIRECTORY_SEPARATOR.'api/cache/config'
                       ?>">Обновление кеша конфига
                    </a>
                </p>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
                         ->script_folder.DIRECTORY_SEPARATOR.'api/auth_session/clear'
                       ?>">Сброс всех сессий авторизации (разлогинить)
                    </a>
                </p>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= 'https://'.$_SERVER['SERVER_NAME'].DIRECTORY_SEPARATOR.Container::get('config')
                         ->script_folder.DIRECTORY_SEPARATOR.'api/session/clear'
                       ?>">💢 Сброс вообще всех сессий
                    </a>
                </p>
            </dd>
            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    🔐 Инструменты
                </h3>
            </dt>

            <dd class='mb-16'>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href="<?= 'https://passwordsgenerator.net/sha1-hash-generator/'
                       ?>">🔑 Генератор хеша для пароля авторизации
                    </a>
                </p>
            </dd>
            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    Видео
                </h3>
            </dt>
            <dd class='mb-16'>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href='https://video.drift.com/v/ab9uVRI6Xs8/'>🎥
                        Настройка скрипта
                    </a>
                </p>
                <p>
                    <a class='font-bold text-indigo-600 dark:text-indigo-400 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-200 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none'
                       target='_blank'
                       href='https://video.drift.com/v/ab6QoM6SMs8/'>🎥
                        Логика 1
                    </a>
                </p>

            </dd>
            <dt class='mb-4'>
                <h3 class='text-xl font-semibold'>
                    📮 Что делать, если что-то работает не так?
                </h3>
            </dt>
            <dd class='mb-16'>
                <p>
                <ul class="list-disc space-y-2">
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-green-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Сделать скриншот или видео ошибки
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-green-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Описать проблему и как ее можно повторить
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-green-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Если есть доступ к папке логов скрипта - сделать архив и выслать вместе с описанием проблемы
                            <code class='text-sm font-bold text-gray-900'>папка_скрипта/logs</code>
                        </p>
                    </li>
                    <li class='flex items-start'>
                            <span class='h-6 flex items-center sm:h-7'>
                              <svg class='flex-shrink-0 h-5 w-5 text-green-400' viewBox='0 0 20 20' fill='currentColor'>
                                <path fill-rule='evenodd'
                                      d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
                                      clip-rule='evenodd'/>
                              </svg>
                            </span>
                        <p class='ml-2'>
                            Не паниковать😉. Ошибка возможна в любом коде, чем подробнее ее описать, тем быстрее ее
                            найдут и исправят.
                        </p>
                    </li>

                </ul>

            </dd>
        </dl>
    </div>
</div>
</body>
</html>