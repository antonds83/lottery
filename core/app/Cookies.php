<?php
namespace Lottery;

class Cookies
{
    /**
     * Sets cookie 'name' with 'value' during the year.
     *
     * @param string $name Cookie name.
     * @param string $value Cookie value.
     * @return void
     */
    public static function setCookie(string $name, string $value): void
    {
        \setcookie($name, $value, time()+86400*365, '/');
    }

    public static function getCookie(string $name): ?string
    {
        return $_COOKIE[$name] ?? null;
    }
}
