<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use App\Modules\Catalogue\Views\Admin\ViewCategoryEdit;
use App\Modules\Catalogue\Views\Admin\ViewItemEdit;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $isCategory = Param::get('is_category')->asInteger(true, "Недопустимое значение параметра.");
        $id = Param::get('id', false)->asInteger(false);
        $parentId = Param::get('parent_pk', false)->asInteger(false);

        if ($isCategory == 1) {
            $viewCategoryEdit = new ViewCategoryEdit();
            $viewCategoryEdit->oCategory = DataSource::factory(Category::cls(), $id);
            $viewCategoryEdit->aCategories = DataSource::factory(Category::cls())->findAll();
            $viewCategoryEdit->parentId = $parentId;
            $this->frame->bindView('content', $viewCategoryEdit);
        } else {
            $viewItemEdit = new ViewItemEdit();
            $viewItemEdit->oItem = DataSource::factory(Item::cls(), $id);
            $this->frame->bindView('content', $viewItemEdit);
        }

        $this->frame->render();
    }

} 