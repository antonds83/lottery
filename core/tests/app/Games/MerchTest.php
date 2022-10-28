<?php

use Lottery\Games\Merch;
use PHPUnit\Framework\TestCase;

final class MerchTest extends TestCase
{
    public function testCheckConsoleCommand()
    {
        $this->assertTrue(Merch::checkConsoleCommand('addmerch', 40));
        $this->assertFalse(Merch::checkConsoleCommand('addmoney', '4 hundreds'));
    }
}
