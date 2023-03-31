<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Player;
use Blackjack\Deck;

require_once(__DIR__ . '../../lib/Player.php');

class PlayerTest extends TestCase
{
    public function testConstructor(): void
    {
        $player = new Player();
        $this->assertSame('あなた', $player->getName());
    }
}
