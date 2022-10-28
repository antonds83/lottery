<?php
use Lottery\Route;
use Lottery\User;
use Lottery\View;

Route::get('/', function() {
    if (User::hasAuthentication()) {
        return View::page('button');
    } else {
        return View::page('login');
    }
});
