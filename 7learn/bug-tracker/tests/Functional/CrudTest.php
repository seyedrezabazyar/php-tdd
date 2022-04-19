<?php

namespace Test\Functional;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use App\Helpers\HttpClient;
use PHPUnit\Framework\TestCase;

class TestFunctional extends TestCase
{
    private $httpClient;
    private $queryBuilder;

    public function setUp(): void
    {
        $pdoConnection = new PDODatabaseConnection($this->getConfig());
        $this->queryBuilder = new PDOQueryBuilder($pdoConnection->connect());

        $this->httpClient = new HttpClient();

        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->httpClient = null;

        parent::tearDown();
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
