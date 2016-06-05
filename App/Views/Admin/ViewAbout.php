<?php
namespace App\Views\Admin;


use SFramework\Classes\View;

class ViewAbout extends View
{

    /** @var array */
    public $credits;

    public function currentRender()
    {
        ?>
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
                <div class="block center-block thumbnail">
                    <div class="bg-primary text-center" style="padding: 5px;">
                        <?= $this->credits['name'] ?> (<?= $this->credits['fullname'] ?>)
                    </div>
                    <div class="bg-success text-center">
                        Версия: <?= $this->credits['version'] ?>
                    </div>
                    <div class="block text-justify" style="padding: 5px;">
                        <h4 class="text-center">Разработчик</h4>
                        <hr/>
                        Имя: <?= $this->credits['author']['name'] ?><br/>
                        Никнейм: <?= $this->credits['author']['nick'] ?><br/>
                        E-mail: <a href="mailto:<?= $this->credits['author']['email'] ?>"><?= $this->credits['author']['email'] ?></a>
                        <hr/>
                        <em><?= $this->credits['description'] ?></em>
                    </div>
                </div>
            </div>
        </div>
        <?
    }

}