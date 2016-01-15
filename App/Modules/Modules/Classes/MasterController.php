<?php
/**
 * Created by PhpStorm.
 * User: metr
 * Date: 22.11.15
 */

namespace App\Modules\Modules\Classes;


use SFramework\Classes\Controller;
use SFramework\Classes\Frame;

class MasterController extends Controller {

    public function __construct(Frame $frame) {
        $this->frame = $frame;
    }

} 