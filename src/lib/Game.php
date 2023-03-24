<?php

namespace Blackjack;

require_once('Deck.php');
require_once('Player.php');
require_once('Dealer.php');
require_once('Hand.php');

class Game
{
    private const FACE_DOWN_CARD_FLG = false;

    private $deck;
    private $player;
    private $dealer;

    public function start(): void
    {
        $this->deck = new Deck();
        $this->player = new Player();
        $this->dealer = new Dealer();

        echo 'ブラックジャックを開始します。' . PHP_EOL;
        $this->player->addCard($this->deck->dealCard());
        $this->player->addCard($this->deck->dealCard());
        $this->dealer->addCard($this->deck->dealCard());
        $this->dealer->addCard($this->deck->dealCard(), self::FACE_DOWN_CARD_FLG);
        // プレイヤーのターン
        $this->player->hitOrStand();
        // ディーラーのターン
        $this->dealer->showSecondCard();
        $this->dealer->hitOrStand();

        $this->player->showHandValue();
        $this->dealer->showHandValue();
        Hand::compareHands($this->player, $this->dealer);
    }
}
