<?php
namespace App\Modules\Catalogue\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Catalogue\Models\Category;
use App\Modules\Catalogue\Models\Item;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerSave extends MasterAdminController {

    public function actionCategory() {
        if (CoreFunctions::isAJAX() && !$this->employeeAuthorizator->authorized()) {
            NotificationLog::instance()->pushError('Нет доступа!');
            $this->response->send();
            return;
        }

        $this->authorizeIfNot();

        $categoryId = Param::post('catalogue-category-id', false)->asInteger(false);

        $name = Param::post('catalogue-category-name')->noEmpty('Заполните поле "Наименование"')->asString();
        $description = Param::post('catalogue-category-description')->asString();
        $parentCategoryId = Param::post('catalogue-category-parent_id')->asInteger(true, 'Поле "Родительская категория" заполнено неверно.');
        $thumbnail = Param::post('catalogue-category-thumbnail', false)->asString();
        $priority = Param::post('catalogue-category-priority', false)->asString();
        $active = (int)Param::post('catalogue-category-active', false)->exists();

        $accept = Param::post('catalogue-category-accept', false);

        if (CoreFunctions::isAJAX() && NotificationLog::instance()->hasProblems()) {
            $this->response->send();
            return;
        }

        /** @var Category $oCategory */
        $oCategory = DataSource::factory(Category::cls(), $categoryId == 0 ? null : $categoryId);
        $oCategory->name = $name;
        $oCategory->description = $description;
        $oCategory->category_id = $parentCategoryId;
        $oCategory->thumbnail = $thumbnail;
        $oCategory->priority = $priority;
        $oCategory->active = $active;
        if ($oCategory->isNew()) {
            $oCategory->deleted = false;
        }

        $oCategory->commit();

        if (!NotificationLog::instance()->hasProblems()) {
            NotificationLog::instance()->pushMessage("Категория \"{$oCategory->name}\" успешно " . ($categoryId == 0 ? 'добавлена' : 'отредактирована') . ".");
        }

        $redirect = "/admin/modules/catalogue/edit/?id={$oCategory->getPrimaryKey()}";
        if ($accept->exists()) {
            $redirect = '/admin/modules/catalogue/' . ($oCategory->category_id == 0 ? '' : "?parent_pk={$oCategory->category_id}");
        } elseif ($categoryId != 0) {
            $redirect = '';
        }

        $this->response->send($redirect);
    }

    public function actionItem() {
        if (CoreFunctions::isAJAX() && !$this->employeeAuthorizator->authorized()) {
            NotificationLog::instance()->pushError('Нет доступа!');
            $this->response->send();
            return;
        }

        $this->authorizeIfNot();

        $categoryId = Param::post('catalogue-item-id', false)->asInteger(false);

        $name = Param::post('catalogue-item-name')->noEmpty('Заполните поле "Наименование"')->asString();
        $description = Param::post('catalogue-item-description')->asString();
        $parentCategoryId = Param::post('catalogue-item-parent_id')->asInteger(true, 'Поле "Родительская категория" заполнено неверно.');
        $price = Param::post('catalogue-item-price', true)->asNumber(true, "Поле \"Цена\" заполнено неверно.");
        $count = Param::post('catalogue-item-count', true)->asInteger(true, "Поле \"Количество\" заполнено неверно.");
        $thumbnail = Param::post('catalogue-item-thumbnail', false)->asString();
        $priority = Param::post('catalogue-item-priority', false)->asString();
        $active = (int)Param::post('catalogue-item-active', false)->exists();

        $accept = Param::post('catalogue-item-accept', false);

        if (CoreFunctions::isAJAX() && NotificationLog::instance()->hasProblems()) {
            $this->response->send();
            return;
        }

        /** @var Item $oItem */
        $oItem = DataSource::factory(Item::cls(), $categoryId == 0 ? null : $categoryId);
        $oItem->name = $name;
        $oItem->description = $description;
        $oItem->category_id = $parentCategoryId;
        $oItem->price = $price;
        $oItem->count = $count;
        $oItem->thumbnail = $thumbnail;
        $oItem->priority = $priority;
        $oItem->active = $active;
        if ($oItem->isNew()) {
            $oItem->deleted = false;
        }

        $oItem->commit();

        if (!NotificationLog::instance()->hasProblems()) {
            NotificationLog::instance()->pushMessage("Позиция \"{$oItem->name}\" успешно " . ($categoryId == 0 ? 'добавлена' : 'отредактирована') . ".");
        }

        $redirect = "/admin/modules/catalogue/edit/?id={$oItem->getPrimaryKey()}";
        if ($accept->exists()) {
            $redirect = '/admin/modules/catalogue/' . ($oItem->category_id == 0 ? '' : "?parent_pk={$oItem->category_id}");
        } elseif ($categoryId != 0) {
            $redirect = '';
        }

        $this->response->send($redirect);
    }

} 