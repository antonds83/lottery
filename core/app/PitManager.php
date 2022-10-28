<?php
namespace Lottery;

use Lottery\Contracts\PitManagerInterface;

class PitManager
{
    /**
     * Returns providers, which implements PitManagerInterface.
     *
     * @return PitManagerInterface[]
     */
    private static function getProviders(): array
    {
        $providers = [];

        foreach (Kernel::getProviders() as $provider) {
            $interfaces = class_implements($provider);
            if (in_array(PitManagerInterface::class, $interfaces)) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }

    /**
     * Formats message for console output.
     *
     * @param string $message Message.
     * @return string
     */
    public static function formatMessage(string $message): string
    {
        return "\e[0;35m$message\e[0m\n";
    }

    /**
     * Formats error for console output.
     *
     * @param string $message Message.
     * @return string
     */
    public static function formatError(string $message): string
    {
        return "\e[0;31m$message\e[0m\n";
    }

    /**
     * Executes console command.
     *
     * @param ...$args
     * @return string|null
     */
    public static function exec(...$args): ?string
    {
        array_shift($args);
        $command = array_shift($args);

        if (!$command) {
            return null;
        }

        foreach (self::getProviders() as $provider) {
            if ($provider::checkConsoleCommand($command, ...$args)) {
                return $provider::execConsoleCommand($command, ...$args);
            }
        }

        return null;
    }
}
