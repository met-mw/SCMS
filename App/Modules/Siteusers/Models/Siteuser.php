<?php
namespace App\Modules\Siteusers\Models;

use DateTime;
use SORM\Entity;

/**
 * Class Siteuser
 * @package App\Modules\Siteusers\Models
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $mail_address
 * @property string $postcode
 * @property int $type
 * @property int $status
 * @property bool $deleted
 * @property bool $active
 * @property-read DateTime $created
 * @property-read DateTime $updated
 */
class Siteuser extends Entity
{
    const SALT = 'oDp1st%';
    const DEFAULT_PASSWORD = 'Temp#Pass';

    const TYPE_USER = 1;
    const TYPE_CONTRACTOR = 2;

    const STATUS_UNCONFIRMED = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_DENIED = 3;

    protected $tableName = 'siteuser';

    public function prepareRelations()
    {
        // TODO: Implement prepareRelations() method.
    }

}