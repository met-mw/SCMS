<?php
namespace App\Models;


use DateTime;
use SORM\Entity;

/**
 * Class NotificationLog
 * @package App\Models
 *
 * @property int $id
 * @property int $code
 * @property string $message
 * @property string $ip
 * @property int $type
 * @property DateTime $date
 */
class NotificationLog extends Entity
{

    protected $tableName = 'notification_log';

}