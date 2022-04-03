<?php

namespace Nikacrm\Core\Base\Traits;

use Nikacrm\Core\Container;
use Nikacrm\Core\Logger;

trait TProfiler
{

    public function run(array $callable, $args = null)
    {
        [$object, $method] = $callable;

        if (Container::get('config')->profiling) {
            $start = microtime(true);

            $logger      = Logger::start(['channel_name' => 'profiling']);
            $memoryUsage = memory_usage();
            $memPeak     = memory_peak();
            $initiator   = get_class($object).'->'.$method;

            $logger->save('▶'.$initiator.'() mem usage: '.$memoryUsage.' peak mem: '
              .$memPeak,
              'debug');

            $returnValue = $object->$method($args);

            $memoryUsage = memory_usage();
            $memPeak     = memory_peak();
            $end         = microtime(true);

            $elapsed = number_format($end - $start, 3);
            $logger->save('⏹'.$initiator.'() mem usage: '.$memoryUsage.' peak mem: '
              .$memPeak.' ⏱ '.$elapsed.' sec.',
              'debug');
        } else {
            $returnValue = $object->$method($args);
        }


        return $returnValue;
    }


}