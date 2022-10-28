<?php

use Lottery\Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testBeforeInsert()
    {
        $fields = ['login' => 'test'];
        $this->assertNotNull(User::beforeInsert($fields));

        $fields = ['password' => 'test'];
        $this->assertNotNull(User::beforeInsert($fields));

        $fields = ['login' => 'test', 'password' => 'test'];
        $this->assertNull(User::beforeInsert($fields));
    }
}
