<?php
namespace App\Modules\Catalogue\Classes\Retrievers;


use SFramework\Classes\DataGrid;
use SORM\DataSource;

class CatalogueRetriever {

    public function getCategoriesAndItems($categoryId, $conditions = '', $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();
        $driver->query('select
	        SQL_CALC_FOUND_ROWS
	        *
        from
	        ((select
		        id, name, description, category_id, priority, null as price
	        from
		        module_catalogue_category
	        where
		        and category_id=' . $categoryId . '
	        order by
		        priority)
	        union
	        (select
		        id, name, description, category_id, priority, price
	        from
		        module_catalogue_item
	        where
		        category_id=' . $categoryId . '
	        order by
		        priority)
	        ) as childs
	    ' . (!empty($conditions) ? " where {$conditions}" : '') . '
	    ' . (is_null($limit) ? '' : " limit {$limit}") . '
	    ' . (is_null($offset) ? '' : " offset {$offset}"));

        return $driver->fetchAll();
    }

} 