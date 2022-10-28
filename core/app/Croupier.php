<?php
namespace Lottery;

use Lottery\Contracts\GameInterface;
use Lottery\Facades\Database;
use function PHPUnit\Framework\containsEqual;

class Croupier
{
    /**
     * Returns providers, which implements GameInterface.
     *
     * @return GameInterface[]
     */
    private static function getProviders(): array
    {
        $providers = [];

        foreach (Kernel::getProviders() as $provider) {
            $interfaces = class_implements($provider);
            if (in_array(GameInterface::class, $interfaces)) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }

    /**
     * After getting round result stored it to the Rounds.
     * Returns round id or null on failure.
     *
     * @param int $userId User id.
     * @param string|int|float $roundResult
     * @param string $code Game code.
     * @param bool $finished Round already finished.
     * @return int|null
     */
    private static function setRoundResult(int $userId, $roundResult, string $code, bool $finished = false): ?int
    {
        $data = [
            'user_id' => $userId,
            'game_code' => $code,
            'round_result' => (string)$roundResult,
            'processed' => $finished ? 'Y' : 'N'
        ];

        if (!Models\Round::beforeInsert($data)) {
            if (Models\Round::query()->insert($data)->execute()) {
                $result = Models\Round::all()
                              ->where()
                              ->andFilter('user_id', '=', $userId)
                              ->order('id', 'desc')
                              ->get(1)
                              ->execute();
                return $result->id ?? null;
            }
        }

        return null;
    }

    /**
     * Prepares simple actions array to local actions format.
     *
     * @param array $rawActions Game actions.
     * @return array
     */
    private static function getActions(array $rawActions): array
    {
        $actions = [];

        foreach ($rawActions as $code => $title) {
            $actions[] = [
                'code' => $code,
                'title' => $title,
            ];
        }

        return $actions;
    }

    /**
     * Returns provider by code.
     *
     * @param string $code Provider code.
     * @return GameInterface|null
     */
    private static function getProviderByCode(string $code)
    {
        foreach (self::getProviders() as $provider) {
            if ($provider::getCode() === $code) {
                return $provider;
            }
        }

        return null;
    }

    /**
     * Selects random game and randomizes game's prize.
     * Returns Game Provider info and result.
     *
     * @param int $userId User id.
     * @return array
     */
    public static function rollDice(int $userId): array
    {
        $games = self::getProviders();
        $game = $games[rand(0, count($games)-1)];

        $result = $game::rollResult($userId);
        $actions = self::getActions($game::getActions());

        $roundId = self::setRoundResult($userId, $result, $game::getCode(), empty($actions));

        return [
            'round' => $roundId,
            'result' => $game::formatResult($result),
            'result_raw' => $result,
            'title' => $game::getTitle(),
            'actions' => $actions,
        ];
    }

    /**
     * User select the action with the prize.
     *
     * @param int $userId User id.
     * @param int $roundId Round id.
     * @param string $action Action code.
     * @return void
     */
    public static function actionRound(int $userId, int $roundId, string $action)
    {
        $result = Models\Round::all()
                    ->where()
                    ->andFilter('id', '=', $roundId)
                    ->get(1)
                    ->execute();

        if ($result && ($result->user_id === $userId)) {

            $game = self::getProviderByCode($result->game_code);

            if ($game && $game::actionRound($result->user_id, $result->round_result, $action)) {
                Models\Round::query()
                    ->update(['processed' => 'Y'])
                    ->where()
                    ->andFilter('id', '=', $roundId)
                    ->execute();
            }
        }
    }

    /**
     * Iterates all round records by game code and limit.
     * For every record executes callback, callback must return true for success processed.
     *
     * @param string $gameCode Game code.
     * @param int $limit Limit rows.
     * @param callable $callback Callback for work with record.
     * @return void
     */
    public static function roundProcessing(string $gameCode, int $limit, callable $callback)
    {
        $table = Models\Round::getTableName();

        $result = Database::arbitraryQuery(
            "
            select 
                * 
            from $table 
            where processed='N' and game_code='$gameCode'
            order by id asc
            limit $limit
        ");

        while ($row = mysqli_fetch_assoc($result)) {

            $success = $callback([
                'user_id' => $row['user_id'],
                'round_result' => $row['round_result'],
            ]);

            if ($success) {
                Models\Round::query()
                    ->update(['processed' => 'Y'])
                    ->where()
                    ->andFilter('id', '=', $row['id'])
                    ->execute();
            }
        }
    }
}
