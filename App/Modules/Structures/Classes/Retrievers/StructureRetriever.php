<?php
namespace App\Modules\Structures\Classes\Retrievers;


use SORM\DataSource;

class StructureRetriever {

    public function getStructures($parentStructureId, $filterConditions = '', $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();

        $sql = 'select
	        SQL_CALC_FOUND_ROWS
	        structure.id,
	        structure.name,
	        structure.description,
	        parent_structure.name parent_structure_name,
	        structure.path,
	        structure.frame,
	        module.alias module_alias,
	        structure.seo_title,
	        structure.seo_description,
        	structure.seo_keywords,
            structure.anchor,
	        structure.priority,
	        structure.is_main,
	        structure.active,
	        structure.deleted
        from
	        structure
	        left join module on module.id = structure.module_id
	        left join structure parent_structure on parent_structure.id = structure.id
	    where
            structure.structure_id = ' . $parentStructureId . '
	    ' . (!empty($filterConditions) ? " and {$filterConditions}" : '') . '
	    ' . (is_null($limit) ? '' : " limit {$limit}") . '
	    ' . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

    public function clearMainFlag() {
        $driver = DataSource::getCurrent();
        $query = 'update structure set is_main=0';
        $driver->query($query);
    }

} 