<?php
namespace Lottery\Games;

use Lottery\Contracts\GameInterface;
use Lottery\Contracts\PitManagerInterface;
use Lottery\Option;

class Merch implements PitManagerInterface, GameInterface
{
    private const OPTIONS = [
        'amount' => 'merch_amount',
    ];

    /**
     * @inheritDoc
     */
    public static function getCode(): string
    {
        return 'Merch';
    }

    /**
     * @inheritDoc
     */
    public static function getTitle(): string
    {
        return 'Fan Merch';
    }

    /**
     * @inheritDoc
     */
    public static function getActions(): array
    {
        return [
            'post' => 'Transfer by Post (by default)',
            'refuse' => 'Refuse',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function checkConsoleCommand(string $command, ...$args): bool
    {
        return
            ($command === 'addmerch') && is_numeric($args[0] ?? null)
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
            case 'addmerch':

                $currentAmount = intval($args[0]) + intval(Option::get(self::OPTIONS['amount']));

                if (Option::set(self::OPTIONS['amount'], $currentAmount)) {
                    return \Lottery\PitManager::formatMessage(
                        "The amount was successfully add, current amount is: $currentAmount"
                    );
                } else {
                    return \Lottery\PitManager::formatError(
                        'Error occurred during the merch add'
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
        $amount = intval(Option::get(self::OPTIONS['amount']));

        if ($amount <= 0) {
            return 0;
        }

        Option::set(self::OPTIONS['amount'], $amount - 1);

        return 1;
    }

    /**
     * @inheritDoc
     */
    public static function formatResult($result): string
    {
        if (!$result) {
            return 'Nothing :(';
        }

        return 'Cool Fan Merch!';
    }

    /**
     * @inheritDoc
     * @see self::getActions()
     */
    public static function actionRound(int $userId, $result, string $code): bool
    {
        $result = intval($result);

        switch ($code)
        {
            case 'refuse':

                $amount = intval(Option::get(self::OPTIONS['amount']));
                Option::set(self::OPTIONS['amount'], $amount + $result);
                return true;

            case 'post':
                return false;
        }

        return false;
    }
}
