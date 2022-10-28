<?php
namespace Lottery\Models;

use Lottery\Contracts\ModelInterface;
use Lottery\Facades\Database;

class Option extends \SimpleORM\Model implements ModelInterface
{
    /**
     * Returns model's table name.
     *
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'options';
    }

    /**
     * @inheritDoc
     */
    public static function install(): void
    {
        Database::arbitraryQuery("
            create table if not exists `options`
            (
                code varchar(50) not null,
                val varchar(50) not null,
                index ix_code (code)
            );
        ");
    }

    /**
     * @inheritDoc
     */
    public static function beforeInsert(array &$fields): ?string
    {
        $fields['code'] = trim($fields['code'] ?? '');
        $fields['val'] = trim($fields['val'] ?? '');

        if ($fields['code'] === '') {
            return 'Code is required field.';
        }

        if ($fields['val'] === '') {
            return 'Val (value) is required field.';
        }

        return null;
    }
}
