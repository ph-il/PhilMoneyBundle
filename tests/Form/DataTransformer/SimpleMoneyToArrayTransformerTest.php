<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests\Form\DataTransformer;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Phil\MoneyBundle\Form\DataTransformer\SimpleMoneyToArrayTransformer;

class SimpleMoneyToArrayTransformerTest extends TestCase
{
    private Money $money;
    private SimpleMoneyToArrayTransformer $transformer;

    protected function setUp(): void
    {
        $currency = new Currency('EUR');
        $this->money = new Money(1000, $currency);
        $this->transformer = (new SimpleMoneyToArrayTransformer(2))
            ->setCurrency($currency->getCode());
    }

    public function testTransformValueToFormData(): void
    {
        self::assertSame(
            ['phil_amount' => '10.00'],
            $this->transformer->transform($this->money)
        );
    }

    public function testTransformNull(): void
    {
        self::assertNull($this->transformer->transform(null));
    }

    public function testReverse(): void
    {
        self::assertSame(
            $this->money->getAmount(),
            $this->transformer->reverseTransform(['phil_amount' => '10.00'])->getAmount()
        );

        self::assertSame(
            $this->money->getCurrency()->getCode(),
            $this->transformer->reverseTransform(['phil_amount' => '10.00'])->getCurrency()->getCode()
        );
    }
}
