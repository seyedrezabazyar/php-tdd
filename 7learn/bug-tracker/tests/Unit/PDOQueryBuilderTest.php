<?php

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    public function testItCanCreateData()
    {
        $pdoConnection = new PDODatabaseConnection($this->getConfig());
        $queryBuilder = new PDOQueryBuilder($pdoConnection->connect());
        $data = [
            'name' => 'First Bug Report',
            'link' => 'http://link.com',
            'user' => 'Seyed Reza Bazyar',
            'email' => 'seyedrezabazyar@gmail.com',
        ];
        $result = $queryBuilder->table('bugs')->create($data);
        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
