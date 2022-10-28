<?php
namespace Lottery\Models;

use Lottery\Contracts\ModelInterface;
use Lottery\Facades\Database;

class Round extends \SimpleORM\Model implements ModelInterface
{
    /**
     * Returns model's table name.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'rounds';
    }

    /**
     * @inheritDoc
     */
    public static function install(): void
    {
        Database::arbitraryQuery("
            create table if not exists rounds
            (
                id int(18) not null auto_increment,
                user_id int(18) not null,
                game_code varchar(50) not null,
                round_result varchar(50) not null,
                processed char(1) not null default 'N',
                primary key(ID),
                index ix_user_id (user_id),
                index ix_game_type (game_code),
                index ix_processed (processed)
            );
        ");
    }

    /**
     * @inheritDoc
     */
    public static function beforeInsert(array &$fields): ?string
    {
        $fields['user_id'] = intval($fields['user_id'] ?? 0);
        $fields['game_code'] = trim($fields['game_code'] ?? '');
        $fields['round_result'] = trim($fields['round_result'] ?? '');

        if (($fields['processed'] ?? 'N') !== 'Y') {
            $fields['processed'] = 'N';
        }

        if (!$fields['user_id'] || !$fields['game_code'] || $fields['round_result'] === '') {
            return 'All fields are required.';
        }

        return null;
    }
}
