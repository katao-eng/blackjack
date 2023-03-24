<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Hand;

require_once(__DIR__ . '../../lib/Hand.php');

class HandTest extends TestCase
{
    public function testGetName(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new \ReflectionObject($stub);
        $property = $reflection->getProperty('name');
        $property->setAccessible(true);
        $property->setValue($stub, 'プレイヤー');
        $this->assertSame('プレイヤー', $stub->getName());
    }

    public function testGetValue(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new \ReflectionObject($stub);
        $property = $reflection->getProperty('value');
        $property->setAccessible(true);
        $property->setValue($stub, 21);
        $this->assertSame(21, $stub->getValue());
    }

    public function testShowHandValue(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new \ReflectionObject($stub);

        $nameProperty = $reflection->getProperty('name');
        $nameProperty->setAccessible(true);
        $nameProperty->setValue($stub, 'プレイヤー');

        $valueProperty = $reflection->getProperty('value');
        $valueProperty->setAccessible(true);
        $valueProperty->setValue($stub, 21);

        $this->expectOutputString('プレイヤーの得点は21です。');
        $stub->showHandValue();
    }

    public function testCompareHands(): void
    {
        // プレイヤーがバーストした場合
        $stubPlayer = $this->getMockForAbstractClass(Hand::class);
        $playerReflection = new \ReflectionObject($stubPlayer);

        $playerNameProperty = $playerReflection->getProperty('name');
        $playerNameProperty->setAccessible(true);
        $playerNameProperty->setValue($stubPlayer, 'プレイヤー');

        $playerValueProperty = $playerReflection->getProperty('value');
        $playerValueProperty->setAccessible(true);
        $playerValueProperty->setValue($stubPlayer, 22);


        $stubDealer = $this->getMockForAbstractClass(Hand::class);
        $dealerReflection = new \ReflectionObject($stubDealer);

        $dealerNameProperty = $dealerReflection->getProperty('name');
        $dealerNameProperty->setAccessible(true);
        $dealerNameProperty->setValue($stubDealer, 'ディーラー');

        $dealerValueProperty = $dealerReflection->getProperty('value');
        $dealerValueProperty->setAccessible(true);
        $dealerValueProperty->setValue($stubDealer, 21);

        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        $output = ob_get_clean();
        $expectedOuntput = 'ディーラーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // ディーラーがバーストした場合
        $playerValueProperty->setValue($stubPlayer, 21);
        $dealerValueProperty->setValue($stubDealer, 22);
        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        $output = ob_get_clean();
        $expectedOuntput = 'プレイヤーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // プレイヤーが勝利する場合
        $playerValueProperty->setValue($stubPlayer, 21);
        $dealerValueProperty->setValue($stubDealer, 20);
        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        $output = ob_get_clean();
        $expectedOuntput = 'プレイヤーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // ディーラーが勝利する場合
        $playerValueProperty->setValue($stubPlayer, 19);
        $dealerValueProperty->setValue($stubDealer, 20);
        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        $output = ob_get_clean();
        $expectedOuntput = 'ディーラーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // 引き分けの場合
        $playerValueProperty->setValue($stubPlayer, 20);
        $dealerValueProperty->setValue($stubDealer, 20);
        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        $output = ob_get_clean();
        $expectedOuntput = '引き分けです。' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);
    }

    public function testIsBusted(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new \ReflectionObject($stub);

        $method = $reflection->getMethod('isBusted');
        $method->setAccessible(true);

        $property = $reflection->getProperty('value');
        $property->setAccessible(true);
        $property->setValue($stub, 22);
        $this->assertTrue($method->invoke($stub));

        $property->setValue($stub, 21);
        $this->assertFalse($method->invoke($stub));
    }
}
