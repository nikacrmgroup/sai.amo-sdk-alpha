<?php

/** @var \Nikacrm\Core\Router $router */

use Nikacrm\App\Controllers\ApiController;
use Nikacrm\App\Controllers\AppController;
use Nikacrm\App\Controllers\PagesController;


/*SYSTEM*/

$router->get('', PagesController::class, 'home');
$router->get('readme', PagesController::class, 'readme', ['roles' => ['admin']]);
$router->get('api/auth', ApiController::class, 'auth');

$router->get('200', PagesController::class, 'test200', ['type' => 'respond_and_proceed']);


//$router->get('api/transactions', ApiController::class, 'getTransactionsJSON');
//$router->get('api/transactions/rendered', ApiController::class, 'getRenderedTransactionsJSON');

$router->get('api/cache/clear', ApiController::class, 'cacheClear', ['roles' => ['admin']]);
$router->post('api/cache/clear', ApiController::class, 'cacheClear', ['roles' => ['admin']]);
//AMO webhooks cache updaters

$router->post('api/cache/update/contacts', ApiController::class, 'updateContactsCache',
  ['type' => 'close_connection_and_proceed']);

$router->get('api/cache/config', ApiController::class, 'updateConfigsCache', ['roles' => ['admin']]);
$router->get('api/auth_session/clear', ApiController::class, 'authSessionClear', ['roles' => ['admin']]);
$router->get('api/session/clear', ApiController::class, 'sessionClear', ['roles' => ['admin']]);
$router->post('api/auth_session/clear', ApiController::class, 'authSessionClear', ['roles' => ['admin']]);

$router->get('login', PagesController::class, 'login');
$router->post('login', PagesController::class, 'login');
$router->get('logout', PagesController::class, 'logout');

/*APP*/

$router->post('api/order/create', ApiController::class, 'orderCreate', ['type' => 'close_connection_and_proceed']);
//$router->post('api/order/create', ApiController::class, 'orderCreate',/* ['type' => 'respond_and_proceed']*/);
//$router->get('api/order/create', ApiController::class, 'orderCreate');


/*USER*/

$router->get('app', AppController::class, 'index');
$router->get('app/transactions', AppController::class, 'showTransactions', ['roles' => ['admin']]);


/*ADMIN*/

/*WEBHOOKS AND AJAX*/

//$router->post('api/products/update', ApiController::class, 'updateProductsPOST');

/*$router->get('webhooks/lead/status', WebhooksController::class, 'leadStatus',
  ['type' => 'respond_and_proceed']); //тестовый урл отладки

$router->post('webhooks/lead/status', WebhooksController::class, 'leadStatus', ['type' => 'respond_and_proceed']);

$router->get('webhooks/lead/update', WebhooksController::class, 'leadUpdate',
  ['type' => 'respond_and_proceed']); //тестовый урл отладки
$router->post('webhooks/lead/update', WebhooksController::class, 'leadUpdate', ['type' => 'respond_and_proceed']);

$router->get('webhooks/lead/delete', WebhooksController::class, 'leadDelete',
  ['type' => 'respond_and_proceed']); //тестовый урл отладки
$router->post('webhooks/lead/delete', WebhooksController::class, 'leadDelete', ['type' => 'respond_and_proceed']);*/