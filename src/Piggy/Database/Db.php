<?php

namespace Piggy\Database;

use CoFund\Common\Exception\NoDatabaseConfigurationException;
use \RedBeanPHP\R as R;

class Db
{
    const DB_SERVER_PROD    = 'prod';
    const DB_SERVER_PROD_RR = 'prod-rr';

    const DB_SERVER_LOGS    = 'logs';
    const DB_SERVER_LOGS_RR = 'logs-rr';

    const DB_SERVER_REPORTS    = 'reports';
    const DB_SERVER_REPORTS_RR = 'reports-rr';

    protected $default = self::DB_SERVER_PROD;

    protected $databases = [];

    protected $selected = null;

    public function switchTo(string $db)
    {
        if (!in_array($db, array_keys($this->databases))) {
            throw new NoDatabaseConfigurationException();
        }

        R::selectDatabase($db);
    }

    public function revert()
    {
        R::selectDatabase($this->default);
    }

    public function addDatabase(string $key, array $config = [])
    {
        $this->selected = $key;

        $this->databases[$key] = true;

        R::addDatabase(
            $key, 
            sprintf("mysql:host=%s; dbname=%s", $config['host'], $config['db_name']),
            $config['user'],
            $config['password'],
            true
        );
    }

    protected function addDatabases(array $databases = [])
    {
        foreach ($databases as $key => $config) {
            self::addDatabases($key, $config);
        }
    }
}
