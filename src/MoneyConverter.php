<?php

declare(strict_types=1);

namespace Tbbc\MoneyBundle;

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

    public static function money(int|string $amount, Currency $currency): Money
    {
        /* @psalm-var int|numeric-string $amount */
        return new Money($amount, $currency);
    }

    public static function currencyPair(Currency $base, Currency $currency, float|string $conversionRatio): CurrencyPair
    {
        if (is_float($conversionRatio)) {
            $conversionRatio = (string) $conversionRatio;
        }
        /* @psalm-var numeric-string $conversionRatio */
        return new CurrencyPair($base, $currency, $conversionRatio);
    }
}
