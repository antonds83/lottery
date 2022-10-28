<?php

use Lottery\Providers\System;
use PHPUnit\Framework\TestCase;

final class SystemTest extends TestCase
{
    public function testCheckConsoleCommand()
    {
        $this->assertTrue(System::checkConsoleCommand('install'));
        $this->assertFalse(System::checkConsoleCommand('inst'));
    }
}
