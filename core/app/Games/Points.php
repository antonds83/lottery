<?php
namespace Lottery\Games;

use Lottery\Contracts\GameInterface;
use Lottery\Contracts\PitManagerInterface;
use Lottery\Croupier;
use Lottery\Option;
use Lottery\User;

class Points implements PitManagerInterface, GameInterface
{
    private const OPTIONS = [
        'min' => 'points_min_range',
        'max' => 'points_max_range',
    ];


    /**
     * @inheritDoc
     */
    public static function getCode(): string
    {
        return 'Points';
    }

    /**
     * @inheritDoc
     */
    public static function getTitle(): string
    {
        return 'Easy Points';
    }

    /**
     * @inheritDoc
     */
    public static function getActions(): array
    {
        return [
        ];
    }

    /**
     * Formats value to float.
     *
     * @param string|int|float $value Raw value;
     * @return float
     */
    private static function formatFloat($value): float
    {
        return (float)str_replace(',', '.', $value);
    }

    /**
     * @inheritDoc
     */
    public static function checkConsoleCommand(string $command, ...$args): bool
    {
        return
            ($command === 'rangepoints') && is_numeric($args[0] ?? null)
                                        && is_numeric($args[1] ?? null)
                                        && $args[0]*$args[1] > 0
                                        && $args[1] > $args[0]
        ;
    }

    /**
     * @inheritDoc
     */
    public static function execConsoleCommand(string $command, ...$args): ?string
    {
        if (!self::checkConsoleCommand($command, ...$args)) {
            return null;
        }

        switch ($command)
        {
            case 'rangepoints':

                $minRange = $args[0];
                $maxRange = $args[1];

                if (Option::set(self::OPTIONS['min'], $minRange) && Option::set(self::OPTIONS['max'], $maxRange)) {
                    return \Lottery\PitManager::formatMessage(
                        "The points range was successfully changed, current minimal points to win is: $minRange, maximum points is: $maxRange"
                    );
                } else {
                    return \Lottery\PitManager::formatError(
                        'Error occurred during the points range changed'
                    );
                }

        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public static function rollResult(int $userId)
    {
        $min = self::formatFloat(Option::get(self::OPTIONS['min']));
        $max = self::formatFloat(Option::get(self::OPTIONS['max']));

        $win = rand($min*100, $max*100)/100;

        User::addPoints($userId, $win);

        return $win;
    }

    /**
     * @inheritDoc
     */
    public static function formatResult($result): string
    {
        return "{$result} points";
    }

    /**
     * @inheritDoc
     * @see self::getActions()
     */
    public static function actionRound(int $userId, $result, string $code): bool
    {
        return true;
    }
}
