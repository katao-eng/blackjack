<?php

namespace Blackjack;

abstract class Hand
{
    protected const BLACKJACK = 21;

    protected $name;
    protected $value;

    abstract public function addCard();
    abstract public function hitOrStand();

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function showHandValue(): void
    {
        echo "{$this->name}の得点は{$this->getValue()}です。";
    }

    public static function compareHands(Hand $player, Hand $dealer): void
    {
        $result = '引き分けです。';

        if ($player->isBusted()) {
            $result = "{$dealer->name}の勝ちです!";
        } elseif ($dealer->isBusted() || $player->getValue() > $dealer->getValue()) {
            $result = "{$player->name}の勝ちです!";
        } elseif ($dealer->getValue() > $player->getValue()) {
            $result = "{$dealer->name}の勝ちです!";
        }

        echo $result . PHP_EOL;
        echo 'ブラックジャックを終了します。' . PHP_EOL;
    }

    protected function isBusted(): bool
    {
        return $this->getValue() > self::BLACKJACK;
    }
}
