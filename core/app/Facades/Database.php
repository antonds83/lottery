<?php
namespace Lottery\Facades;

use Lottery\Config;

class Database
{
    /**
     * @var bool Flag means establishing connecting to DB.
     */
    private static bool $connected = false;

    /**
     * Returns database configuration.
     *
     * @return array
     */
    private static function getConfig(): array
    {
        return array_merge(
            ['host' => null, 'user' => null, 'pass' => null, 'name' => null],
            Config::get('database', 'connections') ?? [],
        );
    }

    /**
     * Establishes connect to database.
     *
     * @return void
     */
    public static function connect()
    {
        if (!self::$connected) {

            self::$connected = true;

            \SimpleORM\Model::config(
                self::getConfig()
            );
        }
    }

    /**
     * Executes arbitrary query to database.
     *
     * @param string $query
     * @return bool|\mysqli_result|null
     */
    public static function arbitraryQuery(string $query)
    {
        $conf = self::getConfig();
        $mysqli = new \mysqli($conf['host'], $conf['user'], $conf['pass'], $conf['name']);

        if (!$mysqli->connect_errno) {
            return $mysqli->query($query);
        }

        return null;
    }
}
