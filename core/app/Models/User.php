<?php
namespace Lottery\Models;

use Lottery\Config;
use Lottery\Contracts\ModelInterface;
use Lottery\Facades\Database;

class User extends \SimpleORM\Model implements ModelInterface
{
    /**
     * Returns model's table name.
     *
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    public static function install(): void
    {
        Database::arbitraryQuery("
            create table if not exists users
            (
                id int(18) not null auto_increment,
                login varchar(50) not null,
                password varchar(50) not null,
                hash varchar(32) not null,
                money decimal(18,2) default 0,
                points decimal(18,2) default 0,
                primary key(ID),
                index ix_login (login),
                index ix_hash (hash)
            );
        ");
    }

    /**
     * @inheritDoc
     */
    public static function beforeInsert(array &$fields): ?string
    {
        $fields['login'] = trim($fields['login'] ?? '');
        $fields['password'] = trim($fields['password'] ?? '');

        if ($fields['login'] === '') {
            return 'Login is required field.';
        }

        if ($fields['password'] === '') {
            return 'Password is required field.';
        }

        $fields['password'] = md5($fields['password'] . '|' . Config::get('app', 'secret'));
        $fields['hash'] = md5($fields['login'] . '|' . uniqid(mt_rand(), true));

        return null;
    }

    /**
     * Returns true if user found by login.
     *
     * @param string $login User login.
     * @return bool
     */
    public static function existsByLogin(string $login): bool
    {
        $result = self::all()->where()->andFilter('login', '=', $login)->execute();
        return $result->count() > 0;
    }
}
