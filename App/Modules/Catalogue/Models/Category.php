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
 * @property int $priority;
 * @property bool $active;
 */
class Category extends Entity {

    protected $tableName = 'module_catalogue_category';

    protected $fieldsDisplayNames = [
        'id' => 'ID',
        'name' => 'Наименование',
        'description' => 'Описание',
        'category_id' => 'Категория',
        'priority' => 'Приоритет',
        'active' => 'Активность'
    ];

    public function prepareRelations() {
        $this->field()
            ->addRelationOTM(DataSource::factory(Category::cls()), 'category_id')
            ->addRelationOTM(DataSource::factory(Item::cls()), 'category_id');
    }

}