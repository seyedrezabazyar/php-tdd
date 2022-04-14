<?php

namespace Tests\Unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Exceptions\configNotValidException;
use App\Exceptions\databaseConnectionException;
use App\Helpers\Config;
use PDO;
use PHPUnit\Framework\TestCase;

class PDODatabaseConnectionTest extends TestCase
{
    public function testPDODatabaseConnectionImplementsDatabaseConnectionInterface()
    {
        $config = $this->getConfig();
        $pdoConnection = new PDODatabaseConnection($config);
        $this->assertInstanceOf(DatabaseConnectionInterface::class, $pdoConnection);
    }

    public function testConnectMethodSouldReturnValidInstance()
    {
        $config = $this->getConfig();
        $pdoConnection = new PDODatabaseConnection($config);
        $pdoHandler = $pdoConnection->connect();
        $this->assertInstanceOf(PDODatabaseConnection::class, $pdoHandler);
        return $pdoHandler;
    }

    /**
     * @depends testConnectMethodSouldReturnValidInstance
     */
    public function testConnectMethodShouldBeConnectToDatabase($pdoHandler)
    {
        $this->assertInstanceOf(PDO::class, $pdoHandler->getConnection());
    }

    public function testItThrowExceptionIfConfigIsInvalid()
    {
        $this->expectException(databaseConnectionException::class);
        $config = $this->getConfig();
        $config['database'] = 'dummy';
        $pdoConnection = new PDODatabaseConnection($config);
        $pdoConnection->connect();
    }

    public function testReceivedConfigHaveRequiredKey()
    {
        $this->expectException(configNotValidException::class);
        $config = $this->getConfig();
        unset($config['db_user']);
        $pdoConnection = new PDODatabaseConnection($config);
        $pdoConnection->connect();
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
