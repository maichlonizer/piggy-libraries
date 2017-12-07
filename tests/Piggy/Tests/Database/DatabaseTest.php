<?php

namespace Piggy\Tests\Database;

use PHPUnit\Framework\TestCase;

use Piggy\Database\Db;
use Piggy\Exceptions\NoDatabaseConfigurationException;
use Piggy\Exceptions\DuplicateDatabaseKeyException;

class DatabaseTest extends TestCase
{
    public function testCreateNewObject()
    {
        $db = new Db(['prod' => ['host' => 'localhost', 'user' => 'root', 'password' => '', 'db_name' => 'test']], 'prod');
        $this->assertInstanceOf('Piggy\Database\Db', $db);
    }

    public function testNoDatabaseConfigurationExceptionIsThrownException()
    {
        $this->expectException(NoDatabaseConfigurationException::class);
        $db = new Db();
        $db->switchTo('logs');
    }

    public function testDuplicateDatabaseKeyExceptionIsThrownException()
    {
        $this->expectException(DuplicateDatabaseKeyException::class);
        $db = new Db(['prod' => ['host' => 'localhost', 'user' => 'root', 'password' => '', 'db_name' => 'prod']], 'prod');
    }

    public function testGetProtectedAttribute()
    {
        $db = new Db();
        $this->assertNull($db->default);
    }

    public function testSwitchToDatabase()
    {
        $db = new Db();
        $this->assertNull($db->default);
        $db->addDatabase('logs', ['host' => 'localhost', 'user' => 'root', 'password' => '', 'db_name' => 'logs']);
        $db->switchTo('logs');

        $this->assertEquals($db->current, 'logs');
    }

    public function testPreviousAndCurrent()
    {
        $databases = [
            'test' => ['host' => 'localhost', 'user' => 'root', 'password' => '', 'db_name' => 'test'],
            'report' => ['host' => 'localhost', 'user' => 'root', 'password' => '', 'db_name' => 'report']
        ];
        $db = new Db($databases, 'test');

        $db->switchTo('report');

        $this->assertEquals($db->current, 'report');
        $this->assertEquals($db->previous, 'test');

        $db->revert();

        $this->assertEquals($db->current, 'test');
    }
}