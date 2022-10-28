<?php
namespace Lottery;

use Lottery\Config;

class Route
{
    /**
     * @var array Registered callbacks for GET routes.
     */
    private static array $getRoutes = [];

    /**
     * @var array Registered callbacks for POST routes.
     */
    private static array $postRoutes = [];

    /**
     * Detects that uri is api or not and includes either api or web routes.
     *
     * @param string &$uri HTTP request uri.
     * @return void
     */
    private static function includeRoutes(string &$uri): void
    {
        $apiPrefix = Config::get('app', 'api_prefix');

        if (strpos($uri, $apiPrefix) === 0) {
            $uri = substr($uri, strlen($apiPrefix)-1);
            require_once __DIR__ . '/../routes/api.php';
        } else {
            require_once __DIR__ . '/../routes/web.php';
        }
    }

    /**
     * Detects callback for given request url and method,
     * performs registered callback and returns result.
     *
     * @param string $method HTTP method (GET or POST).
     * @param string $uri HTTP request uri.
     * @return callable|null
     */
    public static function make(string $method, string $uri): ?callable
    {
        self::includeRoutes($uri);

        if ($method === 'POST') {
            $routes = self::$postRoutes;
        } else {
            $routes = self::$getRoutes;
        }

        if ($routes[$uri] ?? null) {
            return $routes[$uri];
        }

        return null;
    }

    /**
     * Registers callback for GET route.
     *
     * @param string $route Route rule
     * @param callable $callback Route callback.
     * @return void
     */
    public static function get(string $route, callable $callback): void
    {
        self::$getRoutes[$route] = $callback;
    }

    /**
     * Registers callback for POST route.
     *
     * @param string $route Route rule.
     * @param callable $callback Route callback.
     * @return void
     */
    public static function post(string $route, callable $callback): void
    {
        self::$postRoutes[$route] = $callback;
    }
}
