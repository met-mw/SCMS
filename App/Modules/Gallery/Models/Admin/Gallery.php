<?php
namespace App\Modules\Gallery\Models\Admin;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class Gallery
 * @package App\Modules\Gallery\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Gallery extends Entity
{

    public function prepareRelations()
    {
        $this->field()->addRelationOTM(DataSource::factory(GalleryItem::cls()), 'gallery_id');
    }

}