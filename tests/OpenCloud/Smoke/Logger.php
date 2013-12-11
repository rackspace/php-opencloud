<?php

namespace OpenCloud\Smoke;

use Psr\Log\AbstractLogger;
use OpenCloud\Common\Log\Logger as CommonLogger;

class Logger extends AbstractLogger
{

    public function log($level, $message, array $context = array())
    {
        $logger = new CommonLogger();
        return $logger->log($level, $message, $context);
    }

}