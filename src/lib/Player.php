<?php

namespace Blackjack;

use Blackjack\Hand;
use Blackjack\Deck;

require_once(__DIR__ . '../../lib/Hand.php');

class Player extends Hand
{
    public function __construct()
    {
        $this->name = 'あなた';
    }

    public function hitOrStand(Deck $deck): void
    {
        do {
            echo $this->getCurrentValueMsg() . self::CARD_DRAW_MSG . PHP_EOL;
            $input = trim(fgets(STDIN));

            while (!in_array($input, self::INPUT_CHECK)) {
                echo self::YN_INPUT_MSG . PHP_EOL;
                echo $this->getCurrentValueMsg() . self::CARD_DRAW_MSG . PHP_EOL;
                $input = trim(fgets(STDIN));
            }

            if ($input === 'Y') {
                $drawCard = $deck->dealCard();
                $this->addCard($drawCard);
                $this->showDrawCard($drawCard);
            }

            if ($this->isBusted()) {
                break;
            }
        } while ($input === 'Y');
    }
}
