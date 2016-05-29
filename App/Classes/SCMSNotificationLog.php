<?php
namespace App\Classes;


use SFramework\Classes\NotificationLog;
use SORM\DataSource;

class SCMSNotificationLog extends NotificationLog
{

    public function logSystemMessage($type, $text, $code = 0)
    {
        /** @var \App\Models\NotificationLog $oSCMSNotificationLog */
        $oSCMSNotificationLog = DataSource::factory(\App\Models\NotificationLog::cls());
        $oSCMSNotificationLog->code = $code;
        $oSCMSNotificationLog->type = $type;
        $oSCMSNotificationLog->message = addslashes($text);
        $oSCMSNotificationLog->ip = $_SERVER["REMOTE_ADDR"];
        $oSCMSNotificationLog->date = date('Y-m-d H:i:s');

        $oSCMSNotificationLog->commit();
    }

    protected function pushAny($type, $text)
    {
        parent::pushAny($type, $text);

        if ($type != self::TYPE_MESSAGE) {
            $this->logSystemMessage($type, $text);
        }
    }

}