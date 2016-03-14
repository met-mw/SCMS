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

    public function prepareRelations() {
        $this->field()
            ->addRelationOTM(DataSource::factory(Category::cls()), 'category_id')
            ->addRelationOTM(DataSource::factory(Item::cls()), 'category_id');
    }

}