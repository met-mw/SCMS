<?php
namespace App\Classes;


use App\Classes\Log\DBLogger;
use SFramework\Classes\NotificationLog;

class SCMSNotificationLog extends NotificationLog
{

    /** @var DBLogger */
    public $DBLogger;

    public function __construct()
    {
        $this->DBLogger = new DBLogger();
    }

    public function pushAny($type, $text, $code = 0)
    {
        parent::pushAny($type, $text);

        switch ($type) {
            case self::TYPE_ERROR:
                $this->DBLogger->error($text);
                break;
            case self::TYPE_WARNING:
                $this->DBLogger->warning($text);
                break;
            case self::TYPE_NOTICE:
                $this->DBLogger->notice($text);
                break;
            default:
                break;
        }
    }

}