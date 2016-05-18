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

    protected $tableName = 'module_gallery_item';

    public function getGallery()
    {
        // $aStructureSettings = $this->findRelationCache($this->getPrimaryKeyName(), StructureSetting::cls());
        /** @var Gallery[] $aGalleries */
        $aGalleries = $this->findRelationCache('gallery_id', Gallery::cls());
        if (empty($aGalleries)) {
            /** @var Gallery $oGalleries */
            $oGalleries = DataSource::factory(Gallery::cls());
            $oGalleries->builder()
                ->where("{$oGalleries->getPrimaryKeyName()}={$this->gallery_id}");

            $aGalleries = $oGalleries->findAll();
            foreach ($aGalleries as $oGallery) {
                $this->addRelationCache('gallery_id', $oGallery);
                $oGallery->addRelationCache($oGallery->getPrimaryKeyName(), $this);
            }
        }

        return isset($aGalleries[0]) ? $aGalleries[0] : null;
    }

}