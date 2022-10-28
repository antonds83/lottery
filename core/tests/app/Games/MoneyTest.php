<?php

use Lottery\Games\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testCheckConsoleCommand()
    {
        $this->assertTrue(Money::checkConsoleCommand('addmoney', 400, 444));
        $this->assertTrue(Money::checkConsoleCommand('addmoney', 40.0));

        $this->assertFalse(Money::checkConsoleCommand('addmoney', '4,00'));
        $this->assertFalse(Money::checkConsoleCommand('addmoney', '4 hundreds'));

        $this->assertTrue(Money::checkConsoleCommand('rangemoney', 1, 100));
        $this->assertTrue(Money::checkConsoleCommand('rangemoney', 1.5, 100));

        $this->assertFalse(Money::checkConsoleCommand('rangemoney', 0, 1));
        $this->assertFalse(Money::checkConsoleCommand('rangemoney', -100, 100));
        $this->assertFalse(Money::checkConsoleCommand('rangemoney', 1000, 100));

        $this->assertTrue(Money::checkConsoleCommand('ratiomoney', 0.2));
        $this->assertTrue(Money::checkConsoleCommand('ratiomoney', 2));

        $this->assertFalse(Money::checkConsoleCommand('ratiomoney', 0));
        $this->assertFalse(Money::checkConsoleCommand('ratiomoney', -2));

        $this->assertTrue(Money::checkConsoleCommand('transfermoney', 2));
        $this->assertFalse(Money::checkConsoleCommand('ratiomoney', 0));
        $this->assertFalse(Money::checkConsoleCommand('ratiomoney', -2));
    }
}
