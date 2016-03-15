<?php
namespace App\Modules\Employees\Classes\Retrievers;


use SORM\DataSource;

class EmployeesRetriever {

    public function getEmployees($filterConditions = '', $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();

        $sql = 'select
	        SQL_CALC_FOUND_ROWS
            id,
            name,
            email,
            created,
            updated,
            active
        from
	        module_employees
	    where
	        deleted = 0
	    ' . (!empty($filterConditions) ? " and {$filterConditions}" : '') . '
	    ' . (is_null($limit) ? '' : " limit {$limit}") . '
	    ' . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

} 