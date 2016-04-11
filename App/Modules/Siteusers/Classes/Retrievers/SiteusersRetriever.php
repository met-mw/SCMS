<?php
namespace App\Modules\Siteusers\Classes\Retrievers;

use SORM\DataSource;

class SiteusersRetriever
{

    public function getSiteusers($conditions = '', $limit = null, $offset = null) {
        $driver = DataSource::getCurrent();

        $sql = 'SELECT
          SQL_CALC_FOUND_ROWS
          *
        FROM
          siteuser
        WHERE
          deleted = 0'
            . (!empty($conditions) ? " and {$conditions}" : '')
            . (is_null($limit) ? '' : " limit {$limit}")
            . (is_null($offset) ? '' : " offset {$offset}");

        $driver->query($sql);

        return $driver->fetchAssoc();
    }

}