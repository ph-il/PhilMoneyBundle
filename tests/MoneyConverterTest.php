<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests;

use PHPUnit\Framework\TestCase;
use Phil\MoneyBundle\MoneyConverter;
use Phil\MoneyBundle\MoneyException;

class MoneyConverterTest extends TestCase
{
    public function testCurrencyThrowExceptionOnNull(): void
    {
        $this->expectException(MoneyException::class);
        $this->expectExceptionMessage('Currency needs to be a string');
        MoneyConverter::currency(null);
    }
}
