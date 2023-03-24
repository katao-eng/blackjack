<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Card;

require_once(__DIR__ . '../../lib/Card.php');

class CardTest extends TestCase
{
    public function testGetSuit(): void
    {
        $card = new Card('クラブ', '5');
        $this->assertSame('クラブ', $card->getSuit());
    }

    public function testGetValue(): void
    {
        $card = new Card('クラブ', '5');
        $this->assertSame('5', $card->getValue());
    }

    public function testToString(): void
    {
        $card = new Card('クラブ', '5');
        $this->assertSame('クラブの5', $card->__toString());
    }
}
