<?php

require_once('Deck.php');
require_once('Player.php');
require_once('Dealer.php');

class Game
{
    private const FACE_DOWN_CARD_FLG = false;

    private $deck;
    private $player1;
    private $dealer;

    public function start(): void
    {
        $this->deck = new Deck();
        $this->player1 = new Player('あなた');
        $this->dealer = new Dealer();

        echo 'ブラックジャックを開始します。' . PHP_EOL;
        $this->player1->addCard($this->deck->dealCard());
        $this->player1->addCard($this->deck->dealCard());
        $this->dealer->addCard($this->deck->dealCard());
        $this->dealer->addCard($this->deck->dealCard(), self::FACE_DOWN_CARD_FLG);
        // プレイヤーのターン
        $this->player1->hitOrStand();
        // ディーラーのターン
        $this->dealer->showSecondCard();
        $this->dealer->hitOrStand();

        $this->player1->showHandValue();
        $this->dealer->showHandValue();
        $this->compareHands();
        echo 'ブラックジャックを終了します。';
    }

    private function compareHands(): void
    {
        if ($this->player1->getValue() > $this->dealer->getValue) {
            echo "{$this->player1->name}の勝ちです!";
        } elseif ($this->player1->getValue() < $this->dealer->getValue) {
            echo "{$this->dealer->name}の勝ちです!";
        } else {
            echo '引き分けです。';
        }
    }
}
