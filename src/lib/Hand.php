<?php

namespace Blackjack;

abstract class Hand
{
    protected const BLACKJACK = 21;
    protected const DEALER_STANDS_AT = 17;
    protected const INPUT_CHECK = array('Y', 'N');
    protected const CARD_DRAW_MSG = 'カードを引きますか？（Y/N）';
    protected const YN_INPUT_MSG = 'YかNで入力してください。';
    protected const EVEN_MSG = '引き分けです。';
    protected const BLACKJACK_END_MSG = 'ブラックジャックを終了します。';

    protected string $name;
    /**
     * Summary of cards
     * @var array<int, Card>
     */
    protected array $cards = array();

    abstract public function hitOrStand(Deck $deck): void;

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
        echo "{$this->name}の得点は{$this->getValue()}です。" . PHP_EOL;
    }

    public function addCard(Card $card): void
    {
        array_push($this->cards, $card);
    }

    public function showDrawCard(Card $card): void
    {
        echo "{$this->name}の引いたカードは{$card->__toString()}です。" . PHP_EOL;
    }

    public function hideDrawCard(): void
    {
        $cardQty = count($this->cards);
        echo "{$this->name}の引いた{$cardQty}枚目のカードはわかりません。" . PHP_EOL;
    }

    public static function compareHands(Hand $player, Hand $dealer): void
    {
        $result = self::EVEN_MSG;

        if ($player->isBusted()) {
            $result = self::getWinnerMsg($dealer);
        } elseif ($dealer->isBusted() || $player->getValue() > $dealer->getValue()) {
            $result = self::getWinnerMsg($player);
        } elseif ($dealer->getValue() > $player->getValue()) {
            $result = self::getWinnerMsg($dealer);
        }

        echo $result . PHP_EOL;
        echo self::BLACKJACK_END_MSG . PHP_EOL;
    }

    private static function getWinnerMsg(Hand $winner): string
    {
        return "{$winner->name}の勝ちです!";
    }

    public function isBusted(): bool
    {
        return $this->getValue() > self::BLACKJACK;
    }

    public function getCurrentValueMsg(): string
    {
        return "{$this->name}の現在の得点は{$this->getValue()}です。";
    }

    protected function isNotDealerHandOverStandLimit(): bool
    {
        return $this->getValue() < self::DEALER_STANDS_AT;
    }
}
