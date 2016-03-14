<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends MasterAdminController {

    public function actionIndex() {
        if (CoreFunctions::isAJAX() && !$this->employeeAuthorizator->authorized()) {
            NotificationLog::instance()->pushError('Нет доступа!');
            $this->response->send();
            return;
        }

        $this->authorizeIfNot();
        $isCategory = Param::get('is_category')->asInteger(true, "Недопустимое значение параметра.");
        $id = Param::get('id')->asInteger();

        if ($isCategory) {
            /** @var Category $oCategory */
            $oCategory = DataSource::factory(Category::cls(), $id);
            $this->categoryDeepDelete($oCategory);

            NotificationLog::instance()->pushMessage("Категория \"{$oCategory->name}\" успешно удалена.");
        } else {
            /** @var Item $oItem */
            $oItem = DataSource::factory(Item::cls(), $id);
            $oItem->deleted = true;
            $oItem->commit();

            NotificationLog::instance()->pushMessage("Категория \"{$oItem->name}\" успешно удалена.");
        }

        $this->response->send();
    }

    public function actionGroup() {
        // TODO: Реализовать групповое удаление
        NotificationLog::instance()->pushMessage('Данная функция отключена в связи с техническими работами.');
        $this->response->send();
    }

    public function categoryDeepDelete(Category $oCategory) {
        $oChildCategories = DataSource::factory(Category::cls());
        $oChildCategories->builder()->where("category_id={$oCategory->getPrimaryKey()}");
        /** @var Category[] $aChildCategories */
        $aChildCategories = $oChildCategories->findAll();
        foreach ($aChildCategories as $oChildCategory) {
            $this->categoryDeepDelete($oChildCategory);
        }

        $oCategory->deleted = true;
        $oCategory->commit();
    }

} 