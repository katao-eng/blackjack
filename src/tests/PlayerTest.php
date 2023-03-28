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

    public function testHitOrStand(): void
    {
        $player = new Player();
        $deck = new Deck();
        ob_start();
        $player->hitOrStand($deck);
        $output = ob_get_clean();
        $expectedOutput = 'あなたの現在の得点は0です。カードを引きますか？（Y/N）' . PHP_EOL;
        $this->assertSame($expectedOutput, $output);
    }
}
