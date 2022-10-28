<?php
namespace Lottery\Providers;

use Lottery\Contracts\ModelInterface;
use Lottery\Contracts\PitManagerInterface;
use Lottery\Kernel;
use Lottery\PitManager;

class System implements PitManagerInterface
{

    /**
     * @inheritDoc
     */
    public static function checkConsoleCommand(string $command, ...$args): bool
    {
        return ($command === 'install');
    }

    /**
     * @inheritDoc
     */
    public static function execConsoleCommand(string $command, ...$args): ?string
    {
        if (!self::checkConsoleCommand($command, ...$args)) {
            return null;
        }

        $result = [];

        foreach (Kernel::getModels() as $model) {

            /** @var ModelInterface $model */
            $interfaces = class_implements($model);

            if (in_array(ModelInterface::class, $interfaces)) {
                $model::install();
                $result[] = PitManager::formatMessage($model . ' successfully installed');
            }
        }

        return implode('', $result);
    }
}
