<?php

use Lottery\Models\Option;
use PHPUnit\Framework\TestCase;

final class OptionTest extends TestCase
{
    public function testBeforeInsert()
    {
        $fields = ['code' => 'test'];
        $this->assertNotNull(Option::beforeInsert($fields));

        $fields = ['val' => 'test'];
        $this->assertNotNull(Option::beforeInsert($fields));

        $fields = ['code' => 'test', 'val' => 'test'];
        $this->assertNull(Option::beforeInsert($fields));
    }
}
