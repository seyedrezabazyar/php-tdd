<?php

namespace Tests\Unit;

use App\Exeptions\configFileNotFoundException;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetFileContentsReturnsArray()
    {
        $config = Config::getFileContents('database');
        $this->assertIsArray($config);
    }

    public function testItThrowsExceptionIfFileNotFound()
    {
        $this->expectException(configFileNotFoundException::class);
        $config = Config::getFileContents('dummy');
    }

    public function testGetMethodReturnsValidData()
    {
        $config = Config::get('database', 'pdo');
        $expectedData = [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'bug_tracker',
            'db_user' => 'root',
            'db_password' => 'root'
        ];
        $this->assertEquals($expectedData, $config);
    }
}
