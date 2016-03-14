<?php
namespace App\Modules\Catalogue\Models;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class Item
 * @package App\Modules\CatalogueRetriever\Models
 *
 * @property int $id;
 * @property string $name;
 * @property string $description;
 * @property int $category_id;
 * @property float $price;
 * @property int $priority;
 * @property bool $active;
 * @property bool $deleted;
 */
class Item extends Entity {

    protected $tableName = 'module_catalogue_item';

    protected $fieldsDisplayNames = [
        'id' => 'ID',
        'name' => 'Наименование',
        'description' => 'Описание',
        'category_id' => 'Категория',
        'price' => 'Цена',
        'priority' => 'Приоритет',
        'active' => 'Активность',
        'deleted' => 'Удалён'
    ];

    public function prepareRelations() {
        $this->field('category_id')->addRelationMTO(DataSource::factory(Category::cls()));
    }

}