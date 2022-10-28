<?php
namespace Lottery;

class Payload
{
    /**
     * @var self|null Current singleton instance.
     */
    private static ?self $instance = null;

    /**
     * @var array All environment variables.
     */
    private array $vars = [];

    private function __construct()
    {
        $this->parse();
    }

    /**
     * Parses environment variables.
     *
     * @return void
     */
    private function parse()
    {
        $request = ['g' => $_GET, 'p' => $_POST, 'c' => $_COOKIE];

        $requestOrder = ini_get('request_order') ?: ini_get('variables_order');
        $requestOrder = preg_replace('#[^cgp]#', '', strtolower($requestOrder)) ?: 'gp';

        $this->vars = [[]];

        foreach (str_split($requestOrder) as $order) {
            $this->vars[] = $request[$order];
        }

        $this->vars = array_merge(...$this->vars);
    }

    /**
     * Returns environment variable by code.
     *
     * @param string $code
     * @return mixed
     */
    public function get(string $code)
    {
        return $this->vars[$code] ?? null;
    }

    /**
     * Returns current singleton instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
