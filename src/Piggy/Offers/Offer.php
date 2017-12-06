<?php

namespace Piggy\Offers;

use \RedBeanPHP\R as R;

class Offer
{
    // Explicitly define the table name for this model
    private $table = 'cofundoffer';

    protected $network = 1;

    public function find(array $params = []) : array
    {
        $query = sprintf("SELECT * FROM %s", $this->table);
        $params = [':network_id'=> $this->network];
        $conditions = [];
        $where = '';

        foreach ($params as $key => $value) {
            $conditions[] = sprintf('%s = :%s', $key, $key);
        }

        $where = $conditions ? "WHERE" . implode(" AND ", $conditions) : "";

        $sql = implode(" ", [$query, $where]);

        $rows = R::getAll($sql, $params);

        return $rows;
    }
}
