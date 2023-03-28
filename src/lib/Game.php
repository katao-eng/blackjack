<?php

namespace Blackjack;
//Todo useに修正
require_once('Deck.php');
require_once('Player.php');
require_once('Dealer.php');
require_once('Hand.php');

class Game
{
    private const HIDE_CARD = false;

    private $deck;
    private $player;
    private $dealer;

    public function start(): void
    {
        $this->deck = new Deck();
        $this->player = new Player();
        $this->dealer = new Dealer();

        echo 'ブラックジャックを開始します。' . PHP_EOL;
        // プレイヤー1枚目ドロー
        $playerCard1 = $this->deck->dealCard();
        $this->player->addCard($playerCard1);
        $this->player->showDrawCard($playerCard1);
        // プレイヤー2枚目ドロー
        $playerCard2 = $this->deck->dealCard();
        $this->player->addCard($playerCard2);
        $this->player->showDrawCard($playerCard2);
        // ディーラー1枚目ドロー
        $dealerCard1 = $this->deck->dealCard();
        $this->dealer->addCard($dealerCard1);
        $this->dealer->showDrawCard($dealerCard1);
        // ディーラー2枚目ドロー
        $this->dealer->addCard($this->deck->dealCard());
        $this->dealer->hideDrawCard();

        // プレイヤーのターン
        $this->player->hitOrStand($this->deck);
        // ディーラーのターン
        $this->dealer->showSecondCard();
        $this->dealer->hitOrStand($this->deck);

        $this->player->showHandValue();
        $this->dealer->showHandValue();
        Hand::compareHands($this->player, $this->dealer);
    }
}
