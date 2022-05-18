<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests\Twig\Extension;

use Locale;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Phil\MoneyBundle\Formatter\MoneyFormatter;
use Phil\MoneyBundle\Twig\Extension\CurrencyExtension;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\TemplateWrapper;

class CurrencyExtensionTest extends TestCase
{
    private CurrencyExtension $extension;
    protected array $variables;

    public function setUp(): void
    {
        Locale::setDefault('fr_FR');
        $this->extension = new CurrencyExtension(new MoneyFormatter(2));
        $this->variables = ['currency' => new Currency('EUR')];
    }

    public function testName(): void
    {
        self::assertSame('phil_money_currency_extension', $this->extension->getName());
    }

    /**
     * @dataProvider getCurrencyTests
     */
    public function testCurrency($template, $expected): void
    {
        $this->assertSame($expected, $this->getTemplate($template)->render($this->variables));
    }

    public function getCurrencyTests(): array
    {
        return [
            ['{{ currency|currency_name }}', 'EUR'],
            ['{{ currency|currency_symbol(".", ",") }}', '€'],
        ];
    }

    protected function getTemplate($template): TemplateWrapper
    {
        $loader = new ArrayLoader(['index' => $template]);
        $twig = new Environment($loader, ['debug' => true, 'cache' => false]);
        $twig->addExtension($this->extension);

        /* @noinspection PhpTemplateMissingInspection */
        return $twig->load('index');
    }
}
