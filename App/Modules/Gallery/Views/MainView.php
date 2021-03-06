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
                    <div class="col-md-3 col-sm-3 col-xs-4">
                        <a class="fancyimage thumbnail" rel="group" href="<?= $oGalleryItem->path ?>">
                            <img class="img-responsive" src="<?= $oGalleryItem->getPathToThumbnail() ?>" />
                        </a>
                    </div>
                <? endforeach; ?>
            </div>
            <? endif; ?>

            <? if ($this->displayType == Gallery::DISPLAY_TYPE_GALLERIA): ?>
                <blockquote>
                    <p>Для перехода в полноэкранный режим сделайте двойное нажатие по галерее.</p>
                </blockquote>
                <div class="galleria">
                    <? foreach ($aGalleryItems as $oGalleryItem): ?>
                        <a href="<?= $oGalleryItem->path ?>">
                            <img src="<?= $oGalleryItem->getPathToThumbnail() ?>" data-big="<?= $oGalleryItem->path ?>" data-title="<?= $oGalleryItem->name ?>" data-description="<?= $oGalleryItem->description ?>" />
                        </a>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
        </div>
        <?
    }

}