<?php

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testThatWeCanGetFirstName()
    {
        $user = new App\Models\User;
        $user->setFirstName('Reza');
        $this->assertEquals($user->getFirstName(), 'Reza');
    }
    public function testThatWeCanGetLastName()
    {
        $user = new App\Models\User;
        $user->setLastName('Bazyar');
        $this->assertEquals($user->getLastName(), 'Bazyar');
    }
    public function testThatWeCanGetFullName()
    {
        $user = new App\Models\User;
        $user->setFirstName('Ali');
        $user->setLastName('Bazyar');
        $this->assertEquals($user->getFullName(), 'Ali Bazyar');
    }

    public function testThatWeCanGetTrimmedFullName()
    {
        $user = new App\Models\User;
        $user->setFirstName('Reza');
        $user->setLastName('Bazyar');
        $this->assertEquals($user->getFullName(), 'Reza Bazyar');
    }
}
