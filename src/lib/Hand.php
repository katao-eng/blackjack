<?php

namespace Blackjack;

abstract class Hand
{
    protected const BLACKJACK = 21;

    protected string $name;
    /**
     * Summary of cards
     * @var array<int, Card>
     */
    protected array $cards = array();

    abstract public function hitOrStand(): void;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Summary of getCards
     * @return array<int, Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function getValue(): int
    {
        $value = 0;
        $aceCount = 0;

        // カードの合計を計算
        foreach ($this->cards as $card) {
            $cardValue = $card->getValue();
            if ($cardValue === 'A') {
                $aceCount++;
            } elseif (is_numeric($cardValue)) {
                $value += intval($cardValue);
            } else {
                $value += 10;
            }
        }

        // Aの判定
        for ($i = 0; $i < $aceCount; $i++) {
            if ($value + 11 <= self::BLACKJACK) {
                $value += 11;
            } else {
                $value += 1;
            }
        }

        return $value;
    }

    public function showHandValue(): void
    {
        echo "{$this->name}の得点は{$this->getValue()}です。";
    }

    public function addCard(Card $card, bool $showFlg = true): void
    {
        array_push($this->cards, $card);
        $cardQty = count($this->cards);

        if ($showFlg) {
            echo "{$this->name}の引いたカードは{$card->__toString()}です。" . PHP_EOL;
        } else {
            echo "{$this->name}の引いた{$cardQty}枚目のカードはわかりません。" . PHP_EOL;
        }
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