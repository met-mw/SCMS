<?php
namespace App\Modules\Gallery\Controllers;


use App\Classes\PublicAreaController;
use App\Modules\Gallery\Models\Admin\Gallery;
use App\Modules\Gallery\Views\MainView;
use SORM\DataSource;

class ControllerMain extends PublicAreaController
{

    public $gallery_id;

    public function actionIndex()
    {
        /** @var Gallery $oGallery */
        $oGallery = DataSource::factory(Gallery::cls(), $this->gallery_id);

        $view = new MainView();
        $view->oGallery = $oGallery;
        $view->oStructure = $this->oStructure;
        $view->render();
    }

}