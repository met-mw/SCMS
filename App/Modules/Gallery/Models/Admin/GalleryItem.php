<?php
namespace App\Modules\Gallery\Models\Admin;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class GalleryItem
 * @package App\Modules\Gallery\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $gallery_id
 * @property string $path
 */
class GalleryItem extends Entity
{

    public function prepareRelations()
    {
        $this->field('gallery_id')->addRelationOTM(DataSource::factory(Gallery::cls()), 'id');
    }

}