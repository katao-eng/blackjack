<?php

namespace Blackjack;

use Blackjack\Deck;
use Blackjack\Player;
use Blackjack\Dealer;
use Blackjack\Hand;
use Blackjack\ComputerPlayer;

require_once(__DIR__ . '../../lib/Deck.php');
require_once(__DIR__ . '../../lib/Player.php');
require_once(__DIR__ . '../../lib/Dealer.php');

class Game
{
    private const BLACKJACK_START_MSG = 'ブラックジャックを開始します。';
    private const BLACKJACK_END_MSG = 'ブラックジャックを終了します。';

    private Deck $deck;
    private Player $player;
    private Dealer $dealer;
    /**
     * @var array<int, ComputerPlayer>
     */
    private array $computerPlayers = array();
    /**
     * @var array<int, Hand>
     */
    private array $players = array();

    public function start(): void
    {
        $this->deck = new Deck();
        $this->player = new Player();
        $this->dealer = new Dealer();
        $this->players = array(
            $this->player,
        );

        echo self::BLACKJACK_START_MSG . PHP_EOL;
        $this->computerPlayers = ComputerPlayer::setComputerPlayers($this->computerPlayers);
        array_push($this->players, $this->computerPlayers);

        // プレイヤーごとに2枚ずつドロー
        foreach ($this->players as $player) {
            $player->initialDeal($this->deck);
        }
        // ディーラー2枚ドロー
        $this->dealer->initialDeal($this->deck);

        // 各プレイヤーのターン
        foreach ($this->players as $i => $player) {
            $player->hitOrStand($this->deck);
            if ($player->isBusted()) {
                $player->showHandValue();
                echo Hand::getLoserMsg($player) . PHP_EOL;
                unset($this->players[$i]);
            }
        }

        // 残プレイヤーがいなければゲーム終了
        if (count($this->players) === 0) {
            echo self::BLACKJACK_END_MSG . PHP_EOL;
            return;
        }

        // ディーラーのターン
        $this->dealer->showSecondCard();
        $this->dealer->hitOrStand($this->deck);
        $this->dealer->showHandValue();

        // 結果発表
        foreach ($this->players as $player) {
            $player->showHandValue();
            Hand::compareHands($player, $this->dealer);
        }
        echo self::BLACKJACK_END_MSG . PHP_EOL;
    }
}
