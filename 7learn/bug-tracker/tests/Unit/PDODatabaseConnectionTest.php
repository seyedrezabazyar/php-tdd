<?php

namespace Tests\Unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDODatabaseConnectionTest extends TestCase
{
    public function testPDODatabaseConnectionImplementsDatabaseConnectionInterface()
    {
        $configs = $this->getConfig();
        $pdoconnection = new PDODatabaseConnection($configs);
        $this->assertInstanceOf(DatabaseConnectionInterface::class, $pdoconnection);
    }

    private function getConfig()
    {
        // $config = Config::get('database', 'pdo');
        # Better method in database.php file...
        // return array_merge($config, [
        //     'database' => 'bug_tracker_testing'
        // ]);

        # Better method
        // $config = Config::get('database', 'pdo_testing');
        // var_dump($config);

        return Config::get('database', 'pdo_testing');
    }
}
