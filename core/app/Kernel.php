<?php
namespace Lottery;

use Lottery\Facades\Database;

class Kernel
{
    /**
     * Returns available Providers.
     *
     * @return string[]
     */
    public static function getProviders(): array
    {
        return [
            Games\Merch::class,
            Games\Money::class,
            Games\Points::class,
            Providers\System::class,
        ];
    }

    /**
     * Returns available Models.
     *
     * @return string[]
     */
    public static function getModels(): array
    {
        return [
            Models\Option::class,
            Models\Round::class,
            Models\User::class,
        ];
    }

    /**
     * Detects callback for given request url and method,
     * performs registered callback and returns result.
     *
     * @return mixed
     */
    public static function routing()
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        $callback = Route::make($method, $uri);
        if ($callback) {
            return $callback();
        }

        return null;
    }
}
