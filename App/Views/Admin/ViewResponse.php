<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewResponse extends View {

    /** @var string */
    public $alertClass;
    /** @var string */
    public $alertText;
    /** @var string */
    public $alertHeader;
    /** @var bool */
    protected $needAlert = false;

    public function __construct($class = null, $header = null, $text = null) {
        $this->optional[] = 'alertClass';
        $this->optional[] = 'alertText';
        $this->optional[] = 'alertHeader';

        if (!is_null($class)) {
            $this->alertClass = $class;
            $this->needAlert = true;
        }
        if (!is_null($header)) {
            $this->alertHeader = $header;
            $this->needAlert = true;
        }
        if (!is_null($text)) {
            $this->alertText = $text;
            $this->needAlert = true;
        }
    }

    public function currentRender() {
        ?>
        <? if ($this->needAlert): ?>
            <div class="alert <?= $this->alertClass ?> center-block" role="<?= (end(explode('-', $this->alertClass))) ?>" style="width: 500px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4><?= $this->alertHeader ?></h4>
                <p><?= $this->alertText ?></p>
            </div>
        <? endif; ?>
        <?
    }

}