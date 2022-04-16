<?php

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    private $queryBuilder;

    public function setUp(): void
    {
        $pdoConnection = new PDODatabaseConnection($this->getConfig());
        $this->queryBuilder = new PDOQueryBuilder($pdoConnection->connect());
        parent::setUp();
    }

    public function testItCanCreateData()
    {
        $result = $this->insertIntoDb();
        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testItCanUpdateData()
    {
        $this->insertIntoDb();

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'Seyed Reza Bazyar')
            ->update(['email' => 'seyedrezabazyar@hotmail.com', 'name' => 'My name']);

        $this->assertEquals(1, $result);
    }

    public function testItCanDeleteRecord()
    {
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();
        $this->insertIntoDb();

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'Seyed Reza Bazyar')
            ->delete();

        $this->assertEquals(4, $result);
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }

    private function insertIntoDb()
    {
        $data = [
            'name' => 'First Bug Report',
            'link' => 'http://link.com',
            'user' => 'Seyed Reza Bazyar',
            'email' => 'seyedrezabazyar@gmail.com',
        ];

        return $this->queryBuilder->table('bugs')->create($data);
    }

    public function tearDown(): void
    {
        $this->queryBuilder->truncateAllTable();

        parent::tearDown();
    }
}
