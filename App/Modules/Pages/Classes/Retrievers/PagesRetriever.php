<?php
namespace App\Modules\Pages\Classes\Retrievers;


use SORM\DataSource;

class PagesRetriever {

    public function getPages($filterConditions = '', $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();

        $sql = 'select
	        SQL_CALC_FOUND_ROWS
            id,
            name,
            description,
            active
        from
	        module_pages
	    ' . (!empty($filterConditions) ? " where {$filterConditions}" : '') . '
	    ' . (is_null($limit) ? '' : " limit {$limit}") . '
	    ' . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

} 