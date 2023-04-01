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

    public function testInitialDeal(): void
    {
        $player = new Player();
        $deck = new Deck();

        $this->assertSame(0, count($player->getCards()));
        $player->initialDeal($deck);
        // プレイヤーのハンドにカードが2枚追加されたことを確認
        $this->assertSame(2, count($player->getCards()));
    }
}
