<?php
namespace App\Modules\Gallery\Views;


use App\Modules\Gallery\Models\Admin\Gallery;
use App\Modules\Structures\Models\Structure;
use SFramework\Classes\View;

class MainView extends View
{

    /** @var Gallery */
    public $oGallery;
    /** @var Structure */
    public $oStructure;

    public function currentRender()
    {
        $aGalleryItems = $this->oGallery->getGalleryItems();

        ?>
        <div class="container">
            <h4><?= $this->oStructure->name ?></h4>
            <hr/>

            <div class="row">
                <? foreach ($aGalleryItems as $oGalleryItem): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6 thumb">
                        <a class="fancyimage" rel="group" href="<?= $oGalleryItem->path ?>">
                            <img class="img-responsive" src="<?= $oGalleryItem->path ?>" />
                        </a>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <?
    }

}