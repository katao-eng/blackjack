<?php

namespace Blackjack;

use Blackjack\Hand;
use Blackjack\Deck;

require_once(__DIR__ . '../../lib/Hand.php');

class Dealer extends Hand
{
    public function __construct()
    {
        $this->name = 'ディーラー';
    }

    public function initialDeal(Deck $deck): void
    {
        // 1枚目ドロー
        $card1 = $deck->dealCard();
        $this->addCard($card1);
        $this->showDrawCard($card1);
        // 2枚目ドロー（非表示）
        $card2 = $deck->dealCard();
        $this->addCard($card2);
        $this->hideDrawCard();
    }

    public function hitOrStand(Deck $deck): void
    {
        do {
            echo $this->getCurrentValueMsg() . PHP_EOL;

            if ($this->isNotDealerHandOverStandLimit()) {
                $drawCard = $deck->dealCard();
                $this->addCard($drawCard);
                $this->showDrawCard($drawCard);
            }
        } while ($this->isNotDealerHandOverStandLimit());
    }

    public function showSecondCard(): void
    {
        echo "{$this->name}の引いた2枚目のカードは{$this->getCards()[1]->__toString()}でした。" . PHP_EOL;
    }
}
