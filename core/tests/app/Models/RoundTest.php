<?php

use Lottery\Models\Round;
use PHPUnit\Framework\TestCase;

final class RoundTest extends TestCase
{
    public function testBeforeInsert()
    {
        $fields = ['user_id' => 'test'];
        $this->assertNotNull(Round::beforeInsert($fields));

        $fields = ['game_code' => 'test'];
        $this->assertNotNull(Round::beforeInsert($fields));

        $fields = ['round_result' => 'test'];
        $this->assertNotNull(Round::beforeInsert($fields));

        $fields = ['user_id' => 'test', 'game_code' => 'test', 'round_result' => 'test'];
        $this->assertNotNull(Round::beforeInsert($fields));

        $fields = ['user_id' => 1, 'game_code' => 'test', 'round_result' => 'test'];
        $this->assertNull(Round::beforeInsert($fields));
    }
}
