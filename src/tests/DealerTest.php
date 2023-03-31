<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Dealer;
use Blackjack\Deck;

require_once(__DIR__ . '../../lib/Dealer.php');

class DealerTest extends TestCase
{
    public function testConstructor(): void
    {
        $dealer = new Dealer();
        $this->assertSame('ディーラー', $dealer->getName());
    }

    public function testHitOrStand()
    {
        $dealer = new Dealer();
        $deck = new Deck();
        $dealer->hitOrStand($deck);

        $this->assertGreaterThanOrEqual(17, $dealer->getValue());
    }

    public function testShowSecondCard()
    {
        $dealer = new Dealer();
        $deck = new Deck();
        $dealer->addCard($deck->dealCard());
        $dealer->addCard($deck->dealCard());

        ob_start();
        $dealer->showSecondCard();
        $output = ob_get_clean();

        $this->assertStringContainsString($dealer->getCards()[1]->__toString(), $output);
    }
}
