<?php
namespace Lottery\Contracts;

interface ModelInterface
{
    /**
     * Executes when run `pitmanager install`.
     *
     * @return void
     */
    public static function install(): void;

    /**
     * Check fields before insert, return error message or null on success.
     *
     * @param array &$fields Insert fields.
     * @return string|null
     */
    public static function beforeInsert(array &$fields): ?string;
}
