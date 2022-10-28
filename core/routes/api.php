<?php

use Lottery\Croupier;
use Lottery\Payload;
use Lottery\Route;
use Lottery\User;

\Lottery\Facades\Database::connect();

header('Content-Type: application/json; charset=utf-8');

Route::post('/auth', function() {

    $payload = Payload::getInstance();

    $errorString = User::authorization(
        $payload->get('login'),
        $payload->get('password'),
    );

    if ($errorString) {
        return json_encode(['error' => $errorString]);
    }

    return json_encode(['result' => true]);
});

Route::post('/roll', function() {

    $payload = Payload::getInstance();

    // analogue of CSRF token
    if (!User::checkCurrentUserByHash($payload->get('token'))) {
        return json_encode(['error' => 'Access denied']);
    }

    $uid = User::getAuthorizedUserData()['id'] ?? 0;

    return json_encode(
        Croupier::rollDice($uid)
    );
});

Route::post('/rollaction', function() {

    $payload = Payload::getInstance();

    // analogue of CSRF token
    if (!User::checkCurrentUserByHash($payload->get('token'))) {
        return json_encode(['error' => 'Access denied']);
    }

    $uid = User::getAuthorizedUserData()['id'] ?? 0;

    Croupier::actionRound(
        $uid,
        $payload->get('round'),
        $payload->get('code')
    );

    return json_encode(true);
});
