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

        // Hit: カードを引く場合（"Y"を入力）
        $this->setUserInput('Y');
        ob_start();
        $player->hitOrStand($deck);
        $output = ob_get_clean();
        $this->assertEquals(1, count($player->getCards()));
        $this->assertStringContainsString($player->getCurrentValueMsg() . Player::CARD_DRAW_MSG, $output);
        $this->assertStringContainsString(Player::YN_INPUT_MSG, $output);

        // Stand: カードを引かない場合（"N"を入力）
        $this->setUserInput('N');
        ob_start();
        $player->hitOrStand($deck);
        $output = ob_get_clean();
        $this->assertEquals(1, count($player->getCards())); // カードが増えていない
        $this->assertStringContainsString($player->getCurrentValueMsg(), $output);
        $this->assertStringNotContainsString(Player::CARD_DRAW_MSG, $output);
        $this->assertStringNotContainsString(Player::YN_INPUT_MSG, $output);
    }

    private function setUserInput($input): void
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $input);
        rewind($stream);
        stream_set_blocking(STDIN, false);
        stream_set_read_buffer(STDIN, 0);
        stream_set_read_buffer($stream, 0);
        stream_copy_to_stream($stream, STDIN);
        fclose($stream);
    }
}
