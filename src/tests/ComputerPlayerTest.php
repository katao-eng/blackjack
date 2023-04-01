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
        $computerPlayer = new ComputerPlayer('CPU');
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

        $minCpuQty = 0;
        $maxCpuQty = 2;
        // コンピュータープレイヤーの人数が下限から上限の範囲内であればfalse
        $this->assertFalse($method->invokeArgs($computerPlayer, array($minCpuQty)));
        $this->assertFalse($method->invokeArgs($computerPlayer, array($maxCpuQty)));
        // コンピュータープレイヤーの人数が下限に満たなければtrue
        $this->assertTrue($method->invokeArgs($computerPlayer, array($minCpuQty - 1)));
        // コンピュータープレイヤーの人数が上限を超えていればtrue
        $this->assertTrue($method->invokeArgs($computerPlayer, array($maxCpuQty + 1)));
    }
}
