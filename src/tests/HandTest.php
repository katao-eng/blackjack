<?php

namespace Blackjack\Tests;

use PHPUnit\Framework\TestCase;
use Blackjack\Hand;
use Blackjack\Card;
use ReflectionObject;

require_once(__DIR__ . '../../lib/Hand.php');

class HandTest extends TestCase
{
    /**
     * Summary of cardsValue16
     * @var array<int, Card>
     */
    private array $cardsValue16;
    /**
     * Summary of cardsValue20
     * @var array<int, Card>
     */
    private array $cardsValue20;
    /**
     * Summary of cardsValue21
     * @var array<int, Card>
     */
    private array $cardsValue21;
    /**
     * Summary of cardsValue22
     * @var array<int, Card>
     */
    private array $cardsValue22;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cardsValue16 = array(
            new Card('ハート', 'Q'),
            new Card('スペード', '6'),
        );
        $this->cardsValue20 = array(
            new Card('ハート', 'A'),
            new Card('スペード', '9'),
        );
        $this->cardsValue21 = array(
            new Card('ハート', 'A'),
            new Card('スペード', 'J'),
        );
        $this->cardsValue22 = array(
            new Card('ハート', '2'),
            new Card('ハート', '10'),
            new Card('スペード', '10'),
        );
    }

    public function testGetName(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'プレイヤー');
        $this->assertSame('プレイヤー', $stub->getName());
    }

    public function testGetValue(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);

        // 通常パターン
        $cards = array(
            new Card('ハート', '4'),
            new Card('スペード', '10'),
        );
        $this->setPrivateProperty($stub, 'cards', $cards);
        $this->assertSame(14, $stub->getValue());

        // 絵札を含む
        $cards = array(
            new Card('ハート', '3'),
            new Card('スペード', 'J'),
        );
        $this->setPrivateProperty($stub, 'cards', $cards);
        $this->assertSame(13, $stub->getValue());

        // 3枚以上
        $cards = array(
            new Card('ハート', '5'),
            new Card('スペード', 'J'),
            new Card('クラブ', '5'),
        );
        $this->setPrivateProperty($stub, 'cards', $cards);
        $this->assertSame(20, $stub->getValue());

        // Aを含む（A=11）
        $cards = array(
            new Card('ハート', 'A'),
            new Card('スペード', '9'),
        );
        $this->setPrivateProperty($stub, 'cards', $cards);
        $this->assertSame(20, $stub->getValue());

        // Aを含む（A=1）
        $cards = array(
            new Card('ハート', 'A'),
            new Card('ダイヤ', '3'),
            new Card('スペード', '9'),
        );
        $this->setPrivateProperty($stub, 'cards', $cards);
        $this->assertSame(13, $stub->getValue());
    }

    public function testShowHandValue(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'プレイヤー');
        $this->setPrivateProperty($stub, 'cards', $this->cardsValue21);

        $this->expectOutputString('プレイヤーの得点は21です。' . PHP_EOL);
        $stub->showHandValue();
    }

    public function testAddCard(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'ディーラー');
        $card = new Card('ハート', 'A');

        $stub->addCard($card);
        $this->assertContains($card, $stub->getCards());
        $this->assertCount(1, $stub->getCards());
        $stub->addCard($card);
        $this->assertCount(2, $stub->getCards());
    }

    public function testShowDrawCard(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'ディーラー');
        $card = new Card('ハート', '3');

        $cards = array(
            $card,
        );
        $this->setPrivateProperty($stub, 'cards', $cards);

        ob_start();
        $stub->showDrawCard($card);
        $output = ob_get_clean();
        $expectedOuntput = 'ディーラーの引いたカードはハートの3です。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);
    }

    public function testHideDrawCard(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stub, 'name', 'ディーラー');

        $cards = array(
            new Card('ハート', '3'),
            new Card('スペード', 'J'),
        );
        $this->setPrivateProperty($stub, 'cards', $cards);

        ob_start();
        $stub->hideDrawCard();
        $output = ob_get_clean();
        $expectedOuntput = 'ディーラーの引いた2枚目のカードはわかりません。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);
    }

    public function testCompareHands(): void
    {
        // プレイヤーのスタブ生成
        $stubPlayer = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stubPlayer, 'name', 'プレイヤー');
        // ディーラーのスタブ生成
        $stubDealer = $this->getMockForAbstractClass(Hand::class);
        $this->setPrivateProperty($stubDealer, 'name', 'ディーラー');

        //Todo プレイヤーバースト時にはcompareHandsメソッド実行されないので、プレイヤーバースト時の条件分岐とテストを削除
        // プレイヤーがバーストした場合
        $this->setPrivateProperty($stubPlayer, 'cards', $this->cardsValue22);
        $this->setPrivateProperty($stubDealer, 'cards', $this->cardsValue21);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'プレイヤーの負けです!' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // ディーラーがバーストした場合
        $this->setPrivateProperty($stubPlayer, 'cards', $this->cardsValue21);
        $this->setPrivateProperty($stubDealer, 'cards', $this->cardsValue22);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'プレイヤーの勝ちです!' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // プレイヤーが勝利する場合
        $this->setPrivateProperty($stubPlayer, 'cards', $this->cardsValue21);
        $this->setPrivateProperty($stubDealer, 'cards', $this->cardsValue20);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'プレイヤーの勝ちです!' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // ディーラーが勝利する場合
        $this->setPrivateProperty($stubPlayer, 'cards', $this->cardsValue20);
        $this->setPrivateProperty($stubDealer, 'cards', $this->cardsValue21);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = 'プレイヤーの負けです!' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);

        // 引き分けの場合
        $this->setPrivateProperty($stubPlayer, 'cards', $this->cardsValue20);
        $this->setPrivateProperty($stubDealer, 'cards', $this->cardsValue20);
        $output = $this->compareHandsToString($stubPlayer, $stubDealer);
        $expectedOuntput = '引き分けです。' . PHP_EOL;
        $this->assertSame($expectedOuntput, $output);
    }

    private function compareHandsToString(Hand $stubPlayer, Hand $stubDealer): string
    {
        ob_start();
        Hand::compareHands($stubPlayer, $stubDealer);
        return ob_get_clean();
    }

    public function testIsBusted(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new ReflectionObject($stub);
        $method = $reflection->getMethod('isBusted');
        $method->setAccessible(true);

        // カード合計が21を超えるとバーストする
        $this->setPrivateProperty($stub, 'cards', $this->cardsValue22);
        $this->assertTrue($method->invoke($stub));

        // カード合計が21以下だとバーストしない
        $this->setPrivateProperty($stub, 'cards', $this->cardsValue21);
        $this->assertFalse($method->invoke($stub));
    }

    /**
     * Summary of setPrivateProperty
     * @param Hand $object
     * @param string $propertyName
     * @param string|array<int, Card> $value
     * @return void
     */
    private function setPrivateProperty(Hand $object, string $propertyName, string|array $value): void
    {
        $reflection = new ReflectionObject($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    public function testIsNotDealerHandOverStandLimit(): void
    {
        $stub = $this->getMockForAbstractClass(Hand::class);
        $reflection = new ReflectionObject($stub);
        $method = $reflection->getMethod('isNotDealerHandOverStandLimit');
        $method->setAccessible(true);

        // カード合計が17以上だとStand
        $this->setPrivateProperty($stub, 'cards', $this->cardsValue20);
        $this->assertFalse($method->invoke($stub));

        // カード合計が17未満だとHit
        $this->setPrivateProperty($stub, 'cards', $this->cardsValue16);
        $this->assertTrue($method->invoke($stub));
    }
}
