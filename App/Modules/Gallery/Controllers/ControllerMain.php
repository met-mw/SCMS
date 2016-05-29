<?php
namespace App\Modules\Gallery\Controllers;


use App\Classes\PublicAreaController;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Views\MainView;
use SORM\DataSource;

class ControllerMain extends PublicAreaController
{

    public $gallery_id;
    public $gallery_display_type;

    public function actionIndex()
    {
        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $this->gallery_id);

        $view = new MainView();
        $view->displayType = $this->gallery_display_type;
        $view->oGallery = $oGallery;
        $view->oStructure = $this->oStructure;
        $view->render();
    }

}