<?php
namespace Lottery\Contracts;

interface PitManagerInterface
{
    /**
     * Must return true, if current Provider services this command and arguments are correct.
     *
     * @param string $command Console command.
     * @param mixed ...$args Console command's arguments.
     * @return bool
     */
    public static function checkConsoleCommand(string $command, ...$args): bool;

    /**
     * After checking command and arguments executes commands.
     *
     * @param string $command Console command.
     * @param mixed ...$args Console command's arguments.
     * @return string|null
     */
    public static function execConsoleCommand(string $command, ...$args): ?string;
}
