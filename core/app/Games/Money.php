<?php
namespace Lottery\Games;

use Lottery\Contracts\GameInterface;
use Lottery\Contracts\PitManagerInterface;
use Lottery\Croupier;
use Lottery\Option;
use Lottery\User;

class Money implements PitManagerInterface, GameInterface
{
    private const OPTIONS = [
        'amount' => 'money_amount',
        'bank_ratio' => 'money_bank_ratio',
        'min' => 'money_min_range',
        'max' => 'money_max_range',
    ];

    /**
     * @inheritDoc
     */
    public static function getCode(): string
    {
        return 'Money';
    }

    /**
     * @inheritDoc
     */
    public static function getTitle(): string
    {
        return 'Easy Money';
    }

    /**
     * @inheritDoc
     */
    public static function getActions(): array
    {
        return [
            'bank' => 'Transfer to bank (by default)',
            'points' => 'Transfer to points',
            'refuse' => 'Refuse',
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
            ($command === 'addmoney') && is_numeric($args[0] ?? null)

            ||

            ($command === 'ratiomoney') && is_numeric($args[0] ?? null) && $args[0] > 0

            ||

            ($command === 'transfermoney') && is_numeric($args[0] ?? null) && $args[0] > 0

            ||

            ($command === 'rangemoney') && is_numeric($args[0] ?? null)
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
            case 'addmoney':

                $currentAmount = self::formatFloat($args[0]) + self::formatFloat(Option::get(self::OPTIONS['amount']));

                if (Option::set(self::OPTIONS['amount'], $currentAmount)) {
                    return \Lottery\PitManager::formatMessage(
                        "The amount was successfully add, current amount is: $currentAmount"
                    );
                } else {
                    return \Lottery\PitManager::formatError(
                        'Error occurred during the money add'
                    );
                }


            case 'ratiomoney':

                if (Option::set(self::OPTIONS['bank_ratio'], $args[0])) {
                    return \Lottery\PitManager::formatMessage(
                        "The ratio was successfully changed, current ratio is: {$args[0]}"
                    );
                } else {
                    return \Lottery\PitManager::formatError(
                        'Error occurred during the changed money ratio'
                    );
                }

            case 'transfermoney':

                $limit = intval($args[0]);

                Croupier::roundProcessing(self::getCode(), $limit, function($item) {

                    /**
                     * some bank api code
                     */

                    return true;
                });

                return \Lottery\PitManager::formatMessage(
                    'Money was transfer to bank'
                );

            case 'rangemoney':

                $minRange = $args[0];
                $maxRange = $args[1];

                if (Option::set(self::OPTIONS['min'], $minRange) && Option::set(self::OPTIONS['max'], $maxRange)) {
                    return \Lottery\PitManager::formatMessage(
                        "The money range was successfully changed, current minimal money to win is: $minRange, maximum money is: $maxRange"
                    );
                } else {
                    return \Lottery\PitManager::formatError(
                        'Error occurred during the money range changed'
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
        $amount = self::formatFloat(Option::get(self::OPTIONS['amount']));
        $min = self::formatFloat(Option::get(self::OPTIONS['min']));
        $max = self::formatFloat(Option::get(self::OPTIONS['max']));

        $max = min($max, $amount);
        $win = rand($min*100, $max*100)/100;

        Option::set(self::OPTIONS['amount'], $amount - $win);

        return $win;
    }

    /**
     * @inheritDoc
     */
    public static function formatResult($result): string
    {
        return "\${$result}";
    }

    /**
     * @inheritDoc
     * @see self::getActions()
     */
    public static function actionRound(int $userId, $result, string $code): bool
    {
        $result = self::formatFloat($result);

        switch ($code)
        {
            case 'points':

                $ratio = self::formatFloat(Option::get(self::OPTIONS['bank_ratio']));
                if (!$ratio) {
                    $ratio = 1;
                }

                User::addPoints($userId, $result * $ratio);

                return true;

            case 'refuse':

                $amount = self::formatFloat(Option::get(self::OPTIONS['amount']));
                Option::set(self::OPTIONS['amount'], $amount + $result);
                return true;

            case 'bank':
                return false;
        }

        return false;
    }
}
