<?php

namespace Blackjack;

class Card
{
    public function __construct(private string $suit, private string $value)
    {
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getSuit() . 'の' . $this->getValue();
    }
}
