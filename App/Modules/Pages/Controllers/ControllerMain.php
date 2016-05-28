<?php
namespace App\Modules\Pages\Controllers;


use App\Classes\PublicAreaController;
use App\Modules\Pages\Models\Page;
use App\Modules\Pages\Views\ViewPage;
use SORM\DataSource;

class ControllerMain extends PublicAreaController {

    public $page_id;

    public function actionIndex() {
        $view = new ViewPage();

        if ($this->page_id != 0) {
            /** @var Page $oPage */
            $oPage = DataSource::factory(Page::cls(), $this->page_id);
            $view->page = $oPage;

            $view->render();
        } else {
            echo 'Страница не назначена.';
        }
    }

}