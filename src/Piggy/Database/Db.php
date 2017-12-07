<?php

namespace Piggy\Database;

use \RedBeanPHP\R as R;

use Piggy\Exceptions\NoDatabaseConfigurationException;
use Piggy\Exceptions\DuplicateDatabaseKeyException;

class Db
{
    protected $default = null;

    protected $current = null;

    protected $previous = null;

    protected $selected = null;

    protected $databases = [];

    public function __construct(array $databases = [], string $default = null)
    {
        $this->addDatabases($databases);

        // if default db is specified
        if ($default) {
            $this->switchTo($default);        
            $this->default = $default;
        }
    }

    public function revert()
    {
        $this->switchTo($this->previous);
    }

    public function switchToDefault()
    {
        $this->switchTo($this->default);
    }

    public function switchTo(string $db)
    {
        try {
            R::selectDatabase($db);

            $this->previous = $this->current;
            $this->current = $db;

        } catch (\RedBeanPHP\RedException $e) {
            throw new NoDatabaseConfigurationException(sprintf('Database %s is not configured properly.', $db));
        }
    }

    public function addDatabases(array $databases = [])
    {
        foreach ($databases as $db => $config) {
            self::addDatabase($db, $config);
        }
    }

    public function addDatabase(string $db, array $config = [])
    {
        try {
            $this->databases[$db] = true;

            R::addDatabase(
                $db, 
                sprintf("mysql:host=%s; dbname=%s", $config['host'], $config['db_name']),
                $config['user'],
                $config['password'],
                true
            );
        } catch (\RedBeanPHP\RedException $e) {
            throw new DuplicateDatabaseKeyException(sprintf('A database has already been specified for this key (%s).', $db));
        }
    }

    public function __get(string $attribute)
    {
        if (property_exists($this, $attribute)) {
            return $this->$attribute;
        }

        return null;
    }
}
