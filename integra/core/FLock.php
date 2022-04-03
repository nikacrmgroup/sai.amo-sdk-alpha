<?php

namespace Nikacrm\Core;

use Fostam\FileLock\Exception\LockFileNotOpenableException;
use Fostam\FileLock\Exception\LockFileOperationFailedException;
use Fostam\FileLock\Exception\LockFileVanishedException;
use Fostam\FileLock\FileLock;
use Nikacrm\Core\Base\Traits\TLogException;

class FLock

{

    use TLogException;
    private const  TMP_PATH = 'locks/';

    private FileLock $fl;
    /**
     * @var \Nikacrm\Core\Logger
     */
    private Logger $logger;
    private string $name;

    private function __construct(string $name, $timeout)
    {
        $this->name   = $name;
        $this->logger = Logger::start(['channel_name' => 'locks']);
        if (!file_exists(self::TMP_PATH)) {
            mkdir(self::TMP_PATH, 0755, true);
        }
        $fl           = new FileLock($name, self::TMP_PATH);
        $this->fl     = $fl;
        try {
            if (!$fl->acquire($timeout)) {
                $this->logger->save("Таймаут $timeout секунд для лока закончился, а он не снялся(", 'warning');
                exit;
            }
            $this->logger->save("💚 Лок $name получен");
        } catch (LockFileNotOpenableException|LockFileOperationFailedException|LockFileVanishedException $e) {
            $this->logException($e);
        }
    }

    /**
     * Возвращает объект обертку. Дополнительно используется рандомная задержка, по молчанию это 3 секунды и таймаут
     * в 100 секунд на получения лока
     * @param  string  $name
     * @param  int  $timeout
     * @param  int  $randDelaySeconds
     * @return \Nikacrm\Core\FLock
     */
    public static function acquire(string $name, int $timeout = 100, int $randDelaySeconds = 3): FLock
    {
        rand_delay($randDelaySeconds);

        return new FLock($name, $timeout);
    }

    /**
     * Обертка метода
     * @return void
     */
    public function release(): void
    {
        try {
            $this->fl->release();
            $this->logger->save("🟢 Лок $this->name отпущен");
        } catch (LockFileOperationFailedException $e) {
            $this->logException($e);
        }
    }
}