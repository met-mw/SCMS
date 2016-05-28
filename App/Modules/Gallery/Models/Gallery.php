<?php
namespace App\Modules\Gallery\Models;


use SORM\DataSource;
use SORM\Entity;

/**
 * Class Gallery
 * @package App\Modules\Gallery\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $deleted
 */
class Gallery extends Entity
{

    protected $tableName = 'module_gallery';

    public function getGalleryItems($orderFieldName = 'id', $order = 'asc')
    {
        /** @var GalleryItem[] $aGalleryItems */
        $aGalleryItems = $this->findRelationCache($this->getPrimaryKeyName(), GalleryItem::cls());
        if (empty($aGalleryItems)) {
            /** @var GalleryItem $oGalleryItems */
            $oGalleryItems = DataSource::factory(GalleryItem::cls());
            $oGalleryItems->builder()
                ->where("gallery_id={$this->getPrimaryKey()}")
                ->order($orderFieldName, $order);

            $aGalleryItems = $oGalleryItems->findAll();
            foreach ($aGalleryItems as $oGalleryItem) {
                $this->addRelationCache($this->getPrimaryKeyName(), $oGalleryItem);
                $oGalleryItem->addRelationCache('gallery_id', $this);
            }
        }

        return $aGalleryItems;
    }

}