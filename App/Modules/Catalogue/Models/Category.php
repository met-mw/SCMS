<?php
namespace App\Modules\Catalogue\Models;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class Category
 * @package App\Modules\CatalogueRetriever\Models
 *
 * @property int $id;
 * @property string $name;
 * @property string $description;
 * @property int $category_id;
 * @property string $thumbnail;
 * @property int $priority;
 * @property bool $active;
 * @property bool $deleted;
 */
class Category extends Entity {

    protected $tableName = 'module_catalogue_category';

    protected $fieldsDisplayNames = [
        'id' => 'ID',
        'name' => 'Наименование',
        'description' => 'Описание',
        'category_id' => 'Категория',
        'thumbnail' => 'Изображение',
        'priority' => 'Приоритет',
        'active' => 'Активность',
        'deleted' => 'Удалён'
    ];

    public function getChildCategories()
    {
        /** @var Category[] $aCategories */
        $aCategories = $this->findRelationCache($this->getPrimaryKeyName(), Category::cls());
        if (empty($aCategories)) {
            $oCategories = DataSource::factory(Category::cls());
            $oCategories->builder()
                ->where("category_id={$this->getPrimaryKey()}");

            $aCategories = $oCategories->findAll();
            foreach ($aCategories as $oCategory) {
                $this->addRelationCache($this->getPrimaryKeyName(), $oCategory);
                $oCategory->addRelationCache('category_id', $this);
            }
        }

        return $aCategories;
    }

    public function getParentCategory()
    {
        /** @var Category[] $aCategories */
        $aCategories = $this->findRelationCache('category_id', Category::cls());
        if (empty($aCategories)) {
            $oCategories = DataSource::factory(Category::cls());
            $oCategories->builder()
                ->where("{$oCategories->getPrimaryKeyName()}={$this->category_id}");

            $aCategories = $oCategories->findAll();
            foreach ($aCategories as $oCategory) {
                $this->addRelationCache('category_id', $oCategory);
                $oCategory->addRelationCache($oCategory->getPrimaryKeyName(), $this);
            }
        }

        return isset($aCategories[0]) ? $aCategories[0] : null;
    }

    public function getItems()
    {
        /** @var Item[] $aItems */
        $aItems = $this->findRelationCache($this->getPrimaryKeyName(), Item::cls());
        if (empty($aItems)) {
            $oItems = DataSource::factory(Item::cls());
            $oItems->builder()
                ->where("category_id={$this->getPrimaryKey()}");

            $aItems = $oItems->findAll();
            foreach ($aItems as $oItem) {
                $this->addRelationCache($this->getPrimaryKeyName(), $oItem);
                $oItem->addRelationCache('category_id', $this);
            }
        }

        return $aItems;
    }

}