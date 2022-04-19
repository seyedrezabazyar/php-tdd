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

    public function testItCanCreateDataWithAPI()
    {
        $data = [
            'json' => [
                'name' => 'API',
                'user' => 'Ahmad',
                'email' => 'api@gmail.com',
                'link' => 'api.com'
            ]
        ];

        $response = $this->httpClient->post('index.php', $data);

        $this->assertEquals(200, $response->getStatusCode());

        $bug = $this->queryBuilder
            ->table('bugs')
            ->where('name', 'API')
            ->where('user', 'Ahmad')
            ->first();

        $this->assertNotNull($bug);

        return $bug;
    }

    /**
     * @depends testItCanCreateDataWithAPI
     */
    public function testItCanUpdateDataWithAPI($bug)
    {
        $data = [
            'json' => [
                'id' => $bug->id,
                'name' => 'API For Update'
            ]
        ];

        $response = $this->httpClient->put('index.php', $data);

        $this->assertEquals(200, $response->getStatusCode());

        $bug = $this->queryBuilder
            ->table('bugs')
            ->find($bug->id);

        $this->assertNotNull($bug);
        $this->assertEquals('API For Update', $bug->name);
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
