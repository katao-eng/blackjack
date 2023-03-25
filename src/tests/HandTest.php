<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Hand;
use ReflectionObject;

require_once(__DIR__ . '../../lib/Hand.php');

class HandTest extends TestCase
{
    public function testGetName(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'プレイヤー');
        $this->assertSame('プレイヤー', $stub->getName());
    }

    public function testGetValue(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'value', 21);
        $this->assertSame(21, $stub->getValue());
    }

    public function testShowHandValue(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'プレイヤー');
        $this->setPrivateProperty($stub, 'value', 21);

        $this->expectOutputString('プレイヤーの得点は21です。');
        $stub->showHandValue();
    }

    public function testCompareHands(): void
    {
        // プレイヤーのスタブ生成
        $stubPlayer = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stubPlayer, 'name', 'プレイヤー');
        // ディーラーのスタブ生成
        $stubDealer = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stubDealer, 'name', 'ディーラー');

        // プレイヤーがバーストした場合
        $this->setPrivateProperty($stubPlayer, 'value', 22);
        $this->setPrivateProperty($stubDealer, 'value', 21);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'ディーラーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // ディーラーがバーストした場合
        $this->setPrivateProperty($stubPlayer, 'value', 21);
        $this->setPrivateProperty($stubDealer, 'value', 22);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'プレイヤーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // プレイヤーが勝利する場合
        $this->setPrivateProperty($stubPlayer, 'value', 21);
        $this->setPrivateProperty($stubDealer, 'value', 20);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'プレイヤーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // ディーラーが勝利する場合
        $this->setPrivateProperty($stubPlayer, 'value', 19);
        $this->setPrivateProperty($stubDealer, 'value', 20);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'ディーラーの勝ちです!' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // 引き分けの場合
        $this->setPrivateProperty($stubPlayer, 'value', 20);
        $this->setPrivateProperty($stubDealer, 'value', 20);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = '引き分けです。' . PHP_EOL . 'ブラックジャックを終了します。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);
    }

    public function testIsBusted(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new ReflectionObject($stub);
        $method = $reflection->getMethod('isBusted');
        $method->setAccessible(true);

        $this->setPrivateProperty($stub, 'value', 22);
        $this->assertTrue($method->invoke($stub));

        $this->setPrivateProperty($stub, 'value', 21);
        $this->assertFalse($method->invoke($stub));
    }

    private function setPrivateProperty(Hand $object, string $propertyName, int|string $value): void
    {
        $reflection = new ReflectionObject($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    private function compareHandsToString(Hand $stubPlayer, Hand $stubDealer): string
    {
        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        return ob_get_clean();
    }
}
