<?php
namespace Lottery\Contracts;

interface GameInterface
{
    /**
     * Returns unique Game code.
     *
     * @return string
     */
    public static function getCode(): string;

    /**
     * Returns Game Title.
     *
     * @return string
     */
    public static function getTitle(): string;

    /**
     * Returns Game actions user can do with the price.
     *
     * @return array
     */
    public static function getActions(): array;

    /**
     * Should return some value of round.
     *
     * @param int $userId User id.
     * @return mixed
     */
    public static function rollResult(int $userId);

    /**
     * Formats result before giving the user.
     *
     * @param mixed $result Result before formatting.
     * @return string
     */
    public static function formatResult($result): string;

    /**
     * Should make some actions after round completed by user.
     * Should returns true, if transaction completed.
     *
     * @param int $userId User id.
     * @param mixed $result Round result.
     * @param string $code Action code.
     * @return bool
     */
    public static function actionRound(int $userId, $result, string $code): bool;
}
