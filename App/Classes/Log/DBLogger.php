<?php
namespace App\Classes\Log;

use App\Models\NotificationLog;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use SORM\DataSource;

class DBLogger extends AbstractLogger implements LoggerInterface
{

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        /** @var NotificationLog $oSCMSNotificationLog */
        $oSCMSNotificationLog = DataSource::factory(NotificationLog::cls());
        $oSCMSNotificationLog->code = 0;
        $oSCMSNotificationLog->type = $level;
        $oSCMSNotificationLog->message = addslashes(trim(strtr($message, $context)));
        $oSCMSNotificationLog->ip = $_SERVER["REMOTE_ADDR"];
        $oSCMSNotificationLog->date = date('Y-m-d H:i:s');

        $oSCMSNotificationLog->commit();
    }

}