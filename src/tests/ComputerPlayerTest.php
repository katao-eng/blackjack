<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\ComputerPlayer;
use Blackjack\Deck;
use ReflectionObject;

require_once(__DIR__ . '../../lib/ComputerPlayer.php');
require_once(__DIR__ . '../../lib/Deck.php');

class ComputerPlayerTest extends TestCase
{
    public function testConstructor(): void
    {
        $computerPlayer = new ComputerPlayer('CPU1');
        $this->assertSame('CPU1', $computerPlayer->getName());
    }

    public function testInitialDeal(): void
    {
        $computerPlayer = new ComputerPlayer('CPU1');
        $deck = new Deck();

        $this->assertSame(0, count($computerPlayer->getCards()));
        $computerPlayer->initialDeal($deck);
        // プレイヤーのハンドにカードが2枚追加されたことを確認
        $this->assertSame(2, count($computerPlayer->getCards()));
    }

    public function testHitOrStand(): void
    {
        $computerPlayer = new ComputerPlayer();
        $deck = new Deck();
        $computerPlayer->hitOrStand($deck);

        $this->assertGreaterThanOrEqual(17, $computerPlayer->getValue());
    }

    public function testIsNotValidPlayerCount(): void
    {
        $computerPlayer = new ComputerPlayer('CPU');
        $reflection = new ReflectionObject($computerPlayer);
        $method = $reflection->getMethod('isNotValidPlayerCount');
        $method->setAccessible(true);

        $constant = $reflection->getReflectionConstant('NON_COMPUTER_PLAYER');
        $constant->setAccessible(true);
        $constMin = $constant->getValue();

        $constant = $reflection->getReflectionConstant('MAX_COMPUTER_PLAYER_QTY');
        $constant->setAccessible(true);
        $constMax = $constant->getValue();

        // コンピュータープレイヤーの人数が下限から上限の範囲内であればfalse
        $this->assertFalse($method->invokeArgs($computerPlayer, array("{$constMin}")));
        $this->assertFalse($method->invokeArgs($computerPlayer, array("{$constMax}")));

        // コンピュータープレイヤーの人数が下限に満たなければtrue
        $this->assertTrue($method->invokeArgs($computerPlayer, array("{$constMin} - 1")));
        // コンピュータープレイヤーの人数が上限を超えていればtrue
        $this->assertTrue($method->invokeArgs($computerPlayer, array("{$constMax} + 1")));
    }
}
