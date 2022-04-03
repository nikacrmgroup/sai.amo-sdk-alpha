<?php

namespace Nikacrm\App\Features\Transactions;

use Nikacrm\Core\Base\Feature;
use Nikacrm\Core\Container;

class TransactionsFeature extends Feature implements ITransactions
{


    private const TRANSACTIONS_LIMIT = 100000;
    /* @var \PDODb $db */
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Container::get('database');
    }

    public function get($user = [])
    {
        $db = $this->db;
        /*Выборка по логину, для админа - все*/
        if (isset($user['login']) && $user['login'] !== 'admin') {
            $db->where('user_id', $user['login']);
        }

        $db->orderBy('created_at', 'DESC');
        $result = $db->get('transactions', self::TRANSACTIONS_LIMIT);

        return $result;
    }

    public function getParamsForUser(array $user)
    {
        if (in_array('spectator_role', $user['roles'], true)) {
            return [

            ];
        }
        $transactions = $this->get($user);
        $transactions = $this->prepare($transactions);
        $headers      = [
          'id',
          //'Тип',

          'Сущность',
          'Дата',
          //'User, id',
          'Операция',
        ];

        $params = [
          'headers'      => $headers,
          'transactions' => $transactions,
        ];

        return $params;
    }

    /**
     * Сохраняем в базу и логи транзакцию.
     * @param  int  $productId  ID товара
     * @param  int  $qty  Количество, которое пишется в базу
     * @param  string  $stageId  Этап склада
     * @param  int  $type  Тип инициализированного действия транзакции, например вебхук или логика скрипта
     * @param  string  $userId  Id пользователя.
     * @param  array  $leadProducts
     * @return void
     */
    public function log(
      string $transactionMessage = '',
      string $value = '',
      int $type = ITransactions::APP_TYPE,
      string $userId = 'script'

    ): void {
        /* @var \Nikacrm\Core\Access $access */
        $access = Container::get('access');
        //$access->authOrLogin();
        //$access->checkAuth();
        //todo убрать userId с фронта - он не нужен?

        $user      = $access->getLoggedInUser() ?? 'rest';
        $userLogin = $user['login'] ?? 'rest';
        $this->save($transactionMessage,
          $type, $value, $userLogin);
        $this->logger->save($transactionMessage);
    }

    public function params()
    {
        /* @var \Nikacrm\Core\Access $access */
        $access = Container::get('access');

        $access->checkAuth();
        $user    = $access->getLoggedInUser();
        $content = '';

        $params = [
          'content' => $content,
        ];

        $transactionsParams = $this->getParamsForUser($user);

        $params = array_merge($params, $transactionsParams);

        return $transactionsParams['transactions'];
    }

    public function render()
    {
        /* @var \Nikacrm\Core\Access $access */
        $access = Container::get('access');

        $access->checkAuth();
        $user    = $access->getLoggedInUser();
        $content = '';

        $params = [
          'content' => $content,
        ];

        $transactionsParams = $this->getParamsForUser($user);

        $params = array_merge($params, $transactionsParams);
        $render = render_twig('app.partials.transactions_table', $params);

        return $render;
    }

    public function save(

      string $desc,
      int $type = ITransactions::APP_TYPE,
      string $value = '',
      string $userId = 'script'
    ) {
        $db         = $this->db;
        $updateData = [
          'type'    => $type,
          'desc'    => $desc,
          'value'   => $value,
          'user_id' => $userId,
        ];

        $id  = $db->insert('transactions', $updateData);
        $err = $db->getLastError();
        if ($err[0] !== '00000') {
            try {
                throw new \RuntimeException('Ошибка записи в БД: '.je($err));
            } catch (\Throwable $e) {
                $this->logException($e);
            }
        }
        $query = $db->getLastQuery();
    }

    public function show()
    {
        //TODO middleware
        //check_access();

        $transactions = $this->get();
        $transactions = $this->prepare($transactions);
        $headers      = [
          'id',
          //'Тип',

          t('transaction.entity'),
          'Дата',
          //'User, id',
          'Операция',
        ];

        $params = [
          'headers'      => $headers,
          'transactions' => $transactions,
        ];

        twig('app.admin.transactions', $params);
    }

    private function prepare(array $transactions)
    {
        $type = [
          ITransactions::WEBHOOK_TYPE => 'Вебхук',
          ITransactions::APP_TYPE     => 'Скрипт',
          ITransactions::INIT_TYPE    => 'Установка',
          ITransactions::USER_TYPE    => 'Пользователь',
          ITransactions::ADMIN_TYPE   => 'Админ',
          ITransactions::OTHER_TYPE   => 'Другое',
        ];


        $result = [];
        foreach ($transactions as $transaction) {
            $orderedTransaction       = [];
            $orderedTransaction['id'] = $transaction['id'];
            //$orderedTransaction['type'] = $type[$transaction['type']];


            $orderedTransaction['value']      = $transaction['value'];
            $orderedTransaction['created_at'] = $transaction['created_at'];
            //$orderedTransaction['user_id']    = $transaction['user_id'];
            $orderedTransaction['desc'] = $transaction['desc'];
            /*Проверяем*/

            $result[] = $orderedTransaction;
        }

        return $result;
    }

}