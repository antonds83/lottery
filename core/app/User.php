<?php
namespace Lottery;

class User
{
    private const USER_HASH_COOKIE = 'UserStoredHash';

    /**
     * @var bool|null Authorized or not current user.
     */
    private static ?bool $authorized = null;

    /**
     * @var array Current authorized user data.
     */
    private static array $currentUserData = [];

    /**
     * Authorizes user by login and raw password.
     *
     * @param string $login User login.
     * @param string $password User password (raw).
     * @return string|null
     */
    public static function authorization(string $login, string $password): ?string
    {
        $userData = [
            'login' => $login,
            'password' => $password,
        ];

        $errorString = Models\User::beforeInsert($userData);
        if ($errorString) {
            return $errorString;
        }

        if (Models\User::existsByLogin($userData['login'])) {
            if (self::login($userData['login'], $userData['password'])) {
                return null;
            } else {
                return 'Login exists, but password is incorrect.';
            }
        }

        $result = Models\User::query()->insert($userData)->execute();
        if ($result) {
            self::login($userData['login'], $userData['password']);
            return null;
        }

        return 'System error.';
    }

    /**
     * Logs in user by login and hashed password. Returns true on success and false on incorrect pair.
     *
     * @param string $login User login.
     * @param string $hashedPassword User hashed password.
     * @return bool
     */
    private static function login(string $login, string $hashedPassword): bool
    {
        $result = Models\User::all()
                      ->where()
                      ->andFilter('login', '=', $login)
                      ->execute();

        if ($result && $result->password === $hashedPassword) {
            Cookies::setCookie(self::USER_HASH_COOKIE, $result->hash);
            return true;
        }

        return false;
    }

    /**
     * Logs in user by hash. Returns true on success and false on fail.
     *
     * @param string $hash User hash.
     * @return bool
     */
    private static function loginByHash(string $hash): bool
    {
        $result = Models\User::all()
                      ->where()
                      ->andFilter('hash', '=', $hash)
                      ->execute();

        if ($result->count()) {
            self::$currentUserData = [
                'id' => $result->id,
                'login' => $result->login,
                'hash' => $result->hash,
                'money' => $result->money,
                'points' => $result->points,
            ];
            return true;
        }

        return false;
    }

    /**
     * Authorizes (or not) current user.
     *
     * @return bool
     */
    public static function doAuthorize(): bool
    {
        if (self::$authorized === null) {

            self::$authorized = false;

            $userHash = Cookies::getCookie(self::USER_HASH_COOKIE);
            if ($userHash) {
                \Lottery\Facades\Database::connect();
                self::$authorized = self::loginByHash($userHash);
            }
        }

        return self::$authorized;
    }

    /**
     * Returns true if current user is authorized.
     *
     * @return bool
     */
    public static function hasAuthentication(): bool
    {
        if (self::$authorized === null) {
            self::doAuthorize();
        }

        return self::$authorized;
    }

    /**
     * Returns authorized user data.
     *
     * @return array
     */
    public static function getAuthorizedUserData(): array
    {
        return self::$currentUserData;
    }

    /**
     * Returns true if current user with same hash.
     *
     * @param string $hash
     * @return bool
     */
    public static function checkCurrentUserByHash(string $hash): bool
    {
        return ((self::$currentUserData['hash'] ?? null) === $hash);
    }

    /**
     * Adds some points to user.
     *
     * @param int $userId User id.
     * @param float $points Points to add.
     * @return void
     */
    public static function addPoints(int $userId, float $points)
    {
        $result = Models\User::all()
                     ->where()
                     ->andFilter('id', '=', $userId)
                     ->execute();
        if ($result) {
            Models\User::query()
                    ->update(['points' => $points + $result->points])
                    ->where()
                    ->andFilter('id', '=', $userId)
                    ->execute();
        }
    }
}
