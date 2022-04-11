<?php

use PHPUnit\Framework\TestCase;

class AnotationTest extends TestCase
{

    // private $value;
    /**
     * @before
     */
    // public function setval()
    // {
    //     $this->value = 6;
    // }
    // public function testCorrectValue()
    // {
    //     $this->value++;
    //     $this->assertEquals($this->value, 7);
    //     return $this->value;
    // }

    private $value;
    public function testCorrectValue()
    {
        $this->value++;
        $this->assertEquals($this->value, 1);
        return $this->value;
    }

    /**
     * @depends testCorrectValue
     */
    public function testCorrectValue2($value)
    {
        $value++;
        $this->assertEquals($value, 2);
    }
}
