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

        $this->queryBuilder->beginTransaction();

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

    public function testItCanUpdateWithMultipleWhere()
    {
        $this->insertIntoDb();
        $this->insertIntoDb(['user' => 'New Name']);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'Seyed Reza Bazyar')
            ->where('link', 'http://link.com')
            ->update(['name' => 'Afret Multiple Where']);

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

    public function testItCanFetchData()
    {
        $this->multipleInsertIntoDb(10);
        $this->multipleInsertIntoDb(10, ['user' => 'Mehrdad Sami']);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('user', 'Mehrdad Sami')
            ->get();

        $this->assertIsArray($result);
        $this->assertCount(10, $result);
    }

    public function testItCanFetchSpecificColumns()
    {
        $this->multipleInsertIntoDb(10);
        $this->multipleInsertIntoDb(10, ['name' => 'Loghman Avand']);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('name', 'Loghman Avand')
            ->get(['name', 'user']);

        $this->assertIsArray($result);
        $this->assertObjectHasAttribute('name', $result[0]);
        $this->assertObjectHasAttribute('user', $result[0]);

        $result = json_decode(json_encode($result[0]), true);

        $this->assertEquals(['name', 'user'], array_keys($result));
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }

    private function insertIntoDb($options = [])
    {
        $data = array_merge([
            'name' => 'First Bug Report',
            'link' => 'http://link.com',
            'user' => 'Seyed Reza Bazyar',
            'email' => 'seyedrezabazyar@gmail.com',
        ], $options);

        return $this->queryBuilder->table('bugs')->create($data);
    }

    public function multipleInsertIntoDb($count, $options = [])
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->insertIntoDb($options);
        }
    }

    public function tearDown(): void
    {
        // $this->queryBuilder->truncateAllTable();

        $this->queryBuilder->rollback();

        parent::tearDown();
    }
}
