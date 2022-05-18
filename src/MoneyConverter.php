<?php

declare(strict_types=1);

namespace Phil\MoneyBundle;

use Money\Currency;
use Money\CurrencyPair;
use Money\Money;

class MoneyConverter
{
    public static function currency(null|string $currency): Currency
    {
        if (!is_string($currency)) {
            throw new MoneyException('Currency needs to be a string');
        }

        if ('' === $currency) {
            throw new MoneyException('Currency cannot be empty');
        }

        return new Currency($currency);
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public static function money(int|string $amount, Currency $currency): Money
    {
        return new Money($amount, $currency);
    }

    /**
     * @psalm-suppress InvalidScalarArgument,ArgumentTypeCoercion
     */
    public static function currencyPair(Currency $base, Currency $currency, float|string $conversionRatio): CurrencyPair
    {
        // This method doesn't exist in moneyphp ^4.0
        // @codeCoverageIgnoreStart
        if (!method_exists(Currency::class, 'isAvailableWithin')) {
            // Moneyphp needs a string in ^4.0
            if (is_float($conversionRatio)) {
                $conversionRatio = (string) $conversionRatio;
            }
        }
        // @codeCoverageIgnoreEnd

        return new CurrencyPair($base, $currency, $conversionRatio);
    }
}
