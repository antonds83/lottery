<?php

use Lottery\Games\Points;
use PHPUnit\Framework\TestCase;

final class PointsTest extends TestCase
{
    public function testCheckConsoleCommand()
    {
        $this->assertTrue(Points::checkConsoleCommand('rangepoints', 1, 100));
        $this->assertTrue(Points::checkConsoleCommand('rangepoints', 1.5, 100));

        $this->assertFalse(Points::checkConsoleCommand('rangepoints', 0, 1));
        $this->assertFalse(Points::checkConsoleCommand('rangepoints', -100, 100));
        $this->assertFalse(Points::checkConsoleCommand('rangepoints', 1000, 100));
    }
}
