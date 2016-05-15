<?php
namespace App\Modules\Catalogue\Models;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class Item
 * @package App\Modules\CatalogueRetriever\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $category_id
 * @property string thumbnail
 * @property float $price
 * @property int $count
 * @property int $priority
 * @property bool $active
 * @property bool $deleted
 */
class Item extends Entity {

    protected $tableName = 'module_catalogue_item';

    protected $fieldsDisplayNames = [
        'id' => 'ID',
        'name' => 'Наименование',
        'description' => 'Описание',
        'category_id' => 'Категория',
        'price' => 'Цена',
        'count' => 'Количество',
        'priority' => 'Приоритет',
        'active' => 'Активность',
        'deleted' => 'Удалён'
    ];

    public function getCategory()
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

}