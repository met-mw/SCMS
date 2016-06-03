<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\CoreFunctions;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerDelete extends AdministratorAreaController {

    public function actionIndex() {
        if (CoreFunctions::isAJAX() && !$this->EmployeeAuthentication->authenticated()) {
            SCMSNotificationLog::instance()->pushError('Нет доступа!');
            $this->Response->send();
            return;
        }

        $this->authorizeIfNot();
        $isCategory = Param::get('is_category')->asInteger(true, "Недопустимое значение параметра.");
        $id = Param::get('id')->asInteger();

        if ($isCategory) {
            /** @var Category $oCategory */
            $oCategory = DataSource::factory(Category::cls(), $id);
            $this->categoryDeepDelete($oCategory);

            SCMSNotificationLog::instance()->pushMessage("Категория \"{$oCategory->name}\" успешно удалена.");
        } else {
            /** @var Item $oItem */
            $oItem = DataSource::factory(Item::cls(), $id);
            $oItem->deleted = true;
            $oItem->commit();

            SCMSNotificationLog::instance()->pushMessage("Позиция \"{$oItem->name}\" успешно удалена.");
        }

        $this->Response->send();
    }

    public function actionGroup() {
        // TODO: Реализовать групповое удаление
        SCMSNotificationLog::instance()->pushMessage('Данная функция отключена в связи с техническими работами.');
        $this->Response->send();
    }

    public function categoryDeepDelete(Category $oCategory) {
        $oChildCategories = DataSource::factory(Category::cls());
        $oChildCategories->builder()->where("category_id={$oCategory->getPrimaryKey()}");
        /** @var Category[] $aChildCategories */
        $aChildCategories = $oChildCategories->findAll();
        foreach ($aChildCategories as $oChildCategory) {
            $this->categoryDeepDelete($oChildCategory);
        }

        /** @var Item $oItems */
        $oItems = DataSource::factory(Item::cls());
        $oItems->builder()->where("category_id={$oCategory->getPrimaryKey()}");
        /** @var Item[] $aItems */
        $aItems = $oItems->findAll();
        foreach ($aItems as $oItem) {
            $oItem->deleted = true;
            $oItem->commit();
        }

        $oCategory->deleted = true;
        $oCategory->commit();
    }

} 