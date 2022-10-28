<?php
namespace Lottery;

class Option
{
    /**
     * Add new option or update exists by code.
     *
     * @param string $name Option name.
     * @param string $value Option value.
     * @return bool
     */
    public static function set(string $name, string $value): bool
    {
        $fields = ['code' => $name, 'val' => $value];

        if (!Models\Option::beforeInsert($fields)) {

            $result = Models\Option::all()
                          ->where()
                          ->andFilter('code', '=', $name)
                          ->execute();
            if ($result->count()) {
                $result = Models\Option::query()
                           ->update($fields)
                           ->where()
                           ->andFilter('code', '=', $name)
                           ->execute();
            } else {
                $result = Models\Option::query()->insert($fields)->execute();
            }

            return (bool)$result;
        }

        return false;
    }

    public static function get(string $name): ?string
    {
        $result = Models\Option::all()
                           ->where()
                           ->andFilter('code', '=', $name)
                           ->execute();

        if ($result->count()) {
            return $result->val;
        }

        return null;
    }
}
