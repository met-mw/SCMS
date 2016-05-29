<?php
namespace App\Modules\Gallery\Views;


use App\Modules\Gallery\Models\Gallery;
use App\Models\Structure;
use SFramework\Classes\View;

class MainView extends View
{

    /** @var int */
    public $displayType;
    /** @var Gallery */
    public $oGallery;
    /** @var Structure */
    public $oStructure;

    public function currentRender()
    {
        $aGalleryItems = $this->oGallery->getGalleryItems('position');

        ?>
        <div class="container">
            <h4><?= $this->oStructure->name ?></h4>
            <hr/>

            <? if ($this->displayType == Gallery::DISPLAY_TYPE_FANCY): ?>
            <div class="row eq-height">
                <? foreach ($aGalleryItems as $oGalleryItem): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a class="fancyimage thumbnail" rel="group" href="<?= $oGalleryItem->path ?>">
                            <img class="img-responsive" src="<?= $oGalleryItem->path ?>" />
                        </a>
                    </div>
                <? endforeach; ?>
            </div>
            <? endif; ?>

            <? if ($this->displayType == Gallery::DISPLAY_TYPE_GALLERIA): ?>
                <div class="galleria">
                    <? foreach ($aGalleryItems as $oGalleryItem): ?>
                        <a href="<?= $oGalleryItem->path ?>">
                            <img src="<?= $oGalleryItem->path ?>" data-big="<?= $oGalleryItem->path ?>" data-title="<?= $oGalleryItem->name ?>" data-description="<?= $oGalleryItem->description ?>" />
                        </a>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
        </div>
        <?
    }

}