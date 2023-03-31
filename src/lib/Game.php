<?php

namespace Blackjack;

use Blackjack\Deck;
use Blackjack\Player;
use Blackjack\Dealer;
use Blackjack\Hand;

require_once(__DIR__ . '../../lib/Deck.php');
require_once(__DIR__ . '../../lib/Player.php');
require_once(__DIR__ . '../../lib/Dealer.php');

class Game
{
    private Deck $deck;
    private Player $player;
    private Dealer $dealer;

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
