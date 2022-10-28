<?php

use Lottery\Facades\Database;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    public function testArbitraryQuery()
    {
        $res = Database::arbitraryQuery('use information_schema');
        $this->assertTrue($res);
    }
}
