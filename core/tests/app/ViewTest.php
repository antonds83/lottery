<?php

use Lottery\View;
use PHPUnit\Framework\TestCase;

final class ViewTest extends TestCase
{
    public function testPage()
    {
        $this->assertNull(View::page('../../pwd'));
        $this->assertNotNull(View::page('login'));
    }
}
