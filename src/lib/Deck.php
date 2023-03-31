<?php

namespace Blackjack;

use Blackjack\Card;

require_once(__DIR__ . '../../lib/Card.php');

class Deck
{
    private const SUITS = array('ハート', 'クラブ', 'スペード', 'ダイヤ');
    private const VALUES = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K');

    /**
     * Summary of cards
     * @var array<int, Card>
     */
    private array $cards = array();

    public function __construct()
    {
        foreach (self::SUITS as $suit) {
            foreach (self::VALUES as $value) {
                $card = new Card($suit, $value);
                array_push($this->cards, $card);
            }
        }

        shuffle($this->cards);
    }

    public function dealCard(): Card
    {
        return array_pop($this->cards);
    }

    /**
     * @return array<int, Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}
