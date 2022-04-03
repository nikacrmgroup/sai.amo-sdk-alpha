<?php

namespace Nikacrm\Core\Base\Traits;

use Nikacrm\Core\Logger;
use Throwable;

trait TLogException
{

    /**
     * @param  \Throwable  $e
     * @return void
     */
    public function logException(Throwable $e): void
    {
        $exceptionMessage = format_exception_message($e);
        Logger::start(['channel_name' => 'exceptions'])->save($exceptionMessage, 'error');
        Logger::start(['channel_name' => 'exceptions'])->save(je(dismount($e)), 'error');
    }

}