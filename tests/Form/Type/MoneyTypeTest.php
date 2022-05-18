<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests\Form\Type;

use Locale;
use Money\Money;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Phil\MoneyBundle\Form\Type\CurrencyType;
use Phil\MoneyBundle\Form\Type\MoneyType;

class MoneyTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $type = new MoneyType(2);
        $currency = new CurrencyType(['USD', 'EUR'], 'EUR');

        return [
            new PreloadedExtension([$type, $currency], []),
        ];
    }

    public function testView(): void
    {
        $view = $this->factory->create(MoneyType::class)
            ->createView();

        self::assertSame('phil_money', $view->vars['id']);
        self::assertCount(2, $view->vars['form']->children);
        $child = $view->vars['form']->children['phil_currency'];
        self::assertSame('phil_money_phil_currency', $child->vars['id']);

        $child = $view->vars['form']->children['phil_amount'];
        self::assertSame('phil_money_phil_amount', $child->vars['id']);
    }

    public function testBindValid(): void
    {
        $form = $this->factory->create(MoneyType::class, null, [
            'currency_type' => CurrencyType::class,
        ]);
        $form->submit([
            'phil_currency' => ['phil_name' => 'EUR'],
            'phil_amount' => '12',
        ]);
        $money = Money::EUR(1200);
        $this->assertSame($money->getAmount(), $form->getData()->getAmount());
        $this->assertSame($money->getCurrency()->getCode(), $form->getData()->getCurrency()->getCode());
    }

    public function testBindDecimalValid(): void
    {
        Locale::setDefault('fr_FR');
        $form = $this->factory->create(MoneyType::class, null, [
            'currency_type' => CurrencyType::class,
        ]);
        $form->submit([
            'phil_currency' => ['phil_name' => 'EUR'],
            'phil_amount' => '12,5',
        ]);
        $money = Money::EUR(1250);
        $this->assertSame($money->getAmount(), $form->getData()->getAmount());
        $this->assertSame($money->getCurrency()->getCode(), $form->getData()->getCurrency()->getCode());
    }

    public function testGreaterThan1000Valid(): void
    {
        Locale::setDefault('fr_FR');
        $form = $this->factory->create(MoneyType::class, null, [
            'currency_type' => CurrencyType::class,
        ]);
        $form->submit([
            'phil_currency' => ['phil_name' => 'EUR'],
            'phil_amount' => '1 252,5',
        ]);
        $money = Money::EUR(125250);
        $this->assertSame($money->getAmount(), $form->getData()->getAmount());
        $this->assertSame($money->getCurrency()->getCode(), $form->getData()->getCurrency()->getCode());
    }

    public function testSetData(): void
    {
        Locale::setDefault('fr_FR');
        $form = $this->factory->create(MoneyType::class, null, [
            'currency_type' => CurrencyType::class,
        ]);
        $form->setData(Money::EUR(120));
        $formView = $form->createView();

        $this->assertSame('1,20', $formView->children['phil_amount']->vars['value']);
    }

    public function testOptions(): void
    {
        Locale::setDefault('fr_FR');
        $form = $this->factory->create(MoneyType::class, null, [
            'currency_type' => CurrencyType::class,
            'amount_options' => [
                'label' => 'Amount',
            ],
            'currency_options' => [
                'label' => 'Currency',
            ],
        ]);
        $form->setData(Money::EUR(120));
        $formView = $form->createView();

        $this->assertSame('1,20', $formView->children['phil_amount']->vars['value']);
    }

    public function testOptionsFailsIfNotValid(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessageMatches('/this_does_not_exists/');

        $this->factory->create(MoneyType::class, null, [
            'currency_type' => CurrencyType::class,
            'amount_options' => [
                'this_does_not_exists' => 'Amount',
            ],
            'currency_options' => [
                'label' => 'Currency',
            ],
        ]);
    }
}
