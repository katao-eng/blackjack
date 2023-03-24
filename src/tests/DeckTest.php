<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Deck;

require_once(__DIR__ . '../../lib/Deck.php');

class DeckTest extends TestCase
{
    public function testDeckSize(): void
    {
        $deck = new Deck();
        $cards = $deck->getCards();
        $this->assertCount(52, $cards);
    }

    public function testDealCard(): void
    {
        $deck = new Deck();
        $card1 = $deck->dealCard();
        $card2 = $deck->dealCard();

        $this->assertInstanceOf(Card::class, $card1);
        $this->assertInstanceOf(Card::class, $card2);
        $this->assertNotEquals($card1, $card2);
        $this->assertCount(50, $deck->getCards());

        $suits = array('ハート', 'クラブ', 'スペード', 'ダイヤ');
        $values = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K');

        $this->assertContains($card1->getSuit, $suits);
        $this->assertContains($card1->getValue, $values);
    }
}
