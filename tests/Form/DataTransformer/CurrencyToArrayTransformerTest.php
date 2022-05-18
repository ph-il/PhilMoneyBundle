<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests\Form\DataTransformer;

use Money\Currency;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Phil\MoneyBundle\Form\DataTransformer\CurrencyToArrayTransformer;

class CurrencyToArrayTransformerTest extends TestCase
{
    public function testTransformCurrencyToArray(): void
    {
        $value = new Currency('EUR');
        $transformer = new CurrencyToArrayTransformer();
        self::assertSame(
            ['phil_name' => 'EUR'],
            $transformer->transform($value)
        );
    }

    public function testTransformNull(): void
    {
        $transformer = new CurrencyToArrayTransformer();
        self::assertNull($transformer->transform(null));
    }

    public function testTransformThrowErrorIfValueIsNotCurrency(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $transformer = new CurrencyToArrayTransformer();
        $transformer->transform('EUR');
    }

    public function testReverseValueToCurrency(): void
    {
        $value = ['phil_name' => 'EUR'];
        $expected = new Currency('EUR');
        $transformer = new CurrencyToArrayTransformer();
        self::assertSame(
            $expected->getCode(),
            $transformer->reverseTransform($value)->getCode()
        );
    }

    public function testReverseToNullIfValueIsNull(): void
    {
        $value = null;
        $transformer = new CurrencyToArrayTransformer();
        self::assertNull($transformer->reverseTransform($value));
    }

    public function testReverseToNullIfFormElementNotSet(): void
    {
        $value = ['phil_name' => null];
        $transformer = new CurrencyToArrayTransformer();
        self::assertNull($transformer->reverseTransform($value));
    }

    public function testReverseFormValueIsNotArray(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $value = 'EUR';
        $transformer = new CurrencyToArrayTransformer();
        $transformer->reverseTransform($value);
    }

    public function testReverseThrowExceptionIfCurrencyCodeNotValid(): void
    {
        $this->expectException(TransformationFailedException::class);
        $value = ['phil_name' => 123];
        $transformer = new CurrencyToArrayTransformer();
        $transformer->reverseTransform($value);
    }
}
