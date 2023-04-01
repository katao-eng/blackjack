<?php

namespace Blackjack;

use Blackjack\Hand;
use Blackjack\Deck;

require_once(__DIR__ . '../../lib/Hand.php');

class ComputerPlayer extends Hand
{
    private const MIN_COMPUTER_PLAYER_QTY = 0;
    private const MAX_COMPUTER_PLAYER_QTY = 2;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function initialDeal(Deck $deck): void
    {
        // 1枚目ドロー（非表示）
        $card1 = $deck->dealCard();
        $this->addCard($card1);
        $this->drawCardMsg($this->getCards());
        // 2枚目ドロー（非表示）
        $card2 = $deck->dealCard();
        $this->addCard($card2);
        $this->drawCardMsg($this->getCards());
    }

    public function hitOrStand(Deck $deck): void
    {
        while ($this->isNotDealerHandOverStandLimit()) {
            $drawCard = $deck->dealCard();
            $this->addCard($drawCard);
            $this->drawCardMsg($this->getCards());
        }
    }

    /**
     * Summary of drawCardMsg
     * @param array<int, Card> $cards
     * @return void
     */
    private function drawCardMsg(array $cards): void
    {
        $cardQty = count($cards);
        echo "{$this->name}が{$cardQty}枚目のカードを引きました。" . PHP_EOL;
    }

    /**
     * @param array<int, ComputerPlayer> $computerPlayers
     * @return array<int, ComputerPlayer>
     */
    public static function setComputerPlayers(array $computerPlayers): array
    {
        echo 'ゲームに参加するコンピュータープレイヤーの人数を入力してください。('
            . self::MIN_COMPUTER_PLAYER_QTY . '~' . self::MAX_COMPUTER_PLAYER_QTY . ')' . PHP_EOL;
        $computerPlayerQty = trim(fgets(STDIN));
        while (!is_numeric($computerPlayerQty) || self::isNotValidPlayerCount(intval($computerPlayerQty))) {
            echo 'コンピュータープレイヤーの人数は'
                . self::MIN_COMPUTER_PLAYER_QTY . '~' . self::MAX_COMPUTER_PLAYER_QTY
                . 'の数値で入力してください。' . PHP_EOL;
            $computerPlayerQty = trim(fgets(STDIN));
        }

        for ($i = 1; $i <= $computerPlayerQty; $i++) {
            $newComputerPlayer = new ComputerPlayer("CPU{$i}");
            array_push($computerPlayers, $newComputerPlayer);
        }

        return $computerPlayers;
    }

    private static function isNotValidPlayerCount(int $computerPlayerQty): bool
    {
        return $computerPlayerQty < self::MIN_COMPUTER_PLAYER_QTY || self::MAX_COMPUTER_PLAYER_QTY < $computerPlayerQty;
    }
}
