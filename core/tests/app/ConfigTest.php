<?php

use Lottery\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testGet()
    {
        $this->assertIsArray(Config::get('database', 'connections'));
        $this->assertNull(Config::get('foo', 'baz'));
        $this->assertNull(Config::get('../bad/foo', 'baz'));
    }
}
