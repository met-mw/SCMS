<?php
namespace App\Modules\Gallery\Classes\Retrievers;


use App\Modules\Gallery\Models\Gallery;
use SORM\DataSource;

class GalleryRetriever
{

    public function getGalleries($filterConditions = [], $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();

        $sql = 'select
	        SQL_CALC_FOUND_ROWS
            id,
            name,
            description
        from
	        module_gallery'
            . ($filterConditions == '' ? '' : " where {$filterConditions}")
            . (is_null($limit) ? '' : " limit {$limit}")
            . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

    public function getGalleryItems(Gallery $oGallery, $filterConditions = [], $limit = null, $offset = null)
    {
        $driver = DataSource::getCurrent();

        $sql = 'select
	        SQL_CALC_FOUND_ROWS
            id,
            name,
            description,
            path,
            position
        from
	        module_gallery_item
	    where
	        gallery_id=' . $oGallery->id
            . ($filterConditions == '' ? '' : " and {$filterConditions}")
            . (is_null($limit) ? '' : " limit {$limit}")
            . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

}