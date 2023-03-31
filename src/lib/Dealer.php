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
        echo "{$this->name}の引いた2枚目のカードは{$this->getCards()[1]->__toString()}でした。";
    }
}
