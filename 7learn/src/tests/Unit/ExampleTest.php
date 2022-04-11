<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    // public function TwoPlusTwoResultInfo()
    public function testTwoPlusTwoResultInfo()
    {
        $this->assertEquals(4, 2 + 2);
    }
}
