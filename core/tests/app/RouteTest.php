<?php

use Lottery\Route;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testGetRoute()
    {
        Route::get('/test', function() {
            return 'OK';
        });

        $this->assertIsCallable(Route::make('GET', '/test'));
        $this->assertIsNotCallable(Route::make('GET', '/test?foo=baz'));
        $this->assertIsNotCallable(Route::make('GET', '/testing'));
    }

    public function testPostRoute()
    {
        Route::post('/test', function() {
            return 'OK';
        });

        $this->assertIsCallable(Route::make('POST', '/test'));
        $this->assertIsNotCallable(Route::make('POST', '/test?foo=baz'));
        $this->assertIsNotCallable(Route::make('POST', '/testing'));
    }
}
