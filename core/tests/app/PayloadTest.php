<?php

use Lottery\Payload;
use PHPUnit\Framework\TestCase;

final class PayloadTest extends TestCase
{
    public function testEnvironment()
    {
        $_GET['foo'] = 'baz2';
        $_POST['foo'] = 'baz';

        $requestOrder = ini_get('request_order') ?: ini_get('variables_order');
        $requestOrder = preg_replace('#[^cgp]#', '', strtolower($requestOrder)) ?: 'gp';

        $payload = Payload::getInstance();

        if (strpos($requestOrder, 'gp') === 0) {
            $this->assertEquals('baz', $payload->get('foo'));
        } else {
            $this->assertEquals('baz2', $payload->get('foo'));
        }
    }
}