<?php
namespace App\Modules\Catalogue\Classes\Retrievers;


use SFramework\Classes\DataGrid;
use SORM\DataSource;

class CatalogueRetriever {

    public function getCategoriesAndItems($categoryId, $conditions = '', $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();

        $sql = 'select
	        SQL_CALC_FOUND_ROWS
	        childs.id,
	        childs.is_category,
	        childs.name,
	        childs.description,
	        case when mcc.name is null then \'Не назначена\' else mcc.name end as category,
	        childs.thumbnail,
	        childs.priority,
	        childs.active,
	        childs.price,
	        childs.count
        from
	        ((select
		        id, true is_category, name, description, category_id, thumbnail, priority, active, null as price, \'-\' as count
	        from
		        module_catalogue_category
	        where
	            deleted = 0
		        and category_id=' . $categoryId . '
	        order by
		        priority)
	        union
	        (select
		        id, false is_category, name, description, category_id, thumbnail, priority, active, price, count
	        from
		        module_catalogue_item
	        where
	            deleted = 0
		        and category_id=' . $categoryId . '
	        order by
		        priority)
	        ) as childs
	        left join module_catalogue_category mcc on mcc.id = childs.category_id
	    ' . (!empty($conditions) ? " where {$conditions}" : '') . '
	    ' . (is_null($limit) ? '' : " limit {$limit}") . '
	    ' . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

} 