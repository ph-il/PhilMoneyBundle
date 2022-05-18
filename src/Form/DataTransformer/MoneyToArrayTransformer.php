<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Form\DataTransformer;

use Money\Currency;
use Money\Money;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer;
use Phil\MoneyBundle\MoneyConverter;

/**
 * Transforms between a Money instance and an array.
 */
class MoneyToArrayTransformer implements DataTransformerInterface
{
    protected MoneyToLocalizedStringTransformer $sfTransformer;

    public function __construct(protected int $decimals = 2)
    {
        $this->sfTransformer = new MoneyToLocalizedStringTransformer($decimals, null, null, 10 ** $this->decimals);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value): ?array
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Money) {
            throw new UnexpectedTypeException($value, 'Money');
        }

        $amount = $this->sfTransformer->transform((float) $value->getAmount());

        return [
            'phil_amount' => $amount,
            'phil_currency' => $value->getCurrency(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): ?Money
    {
        if (null === $value) {
            return null;
        }

        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }
        if (!isset($value['phil_amount']) || !isset($value['phil_currency'])) {
            return null;
        }

        $amount = (string) $value['phil_amount'];
        $amount = str_replace(' ', '', $amount);
        $amount = (float) $this->sfTransformer->reverseTransform($amount);
        $amount = round($amount);
        $amount = (int) $amount;

        /** @var string|Currency $currency */
        $currency = $value['phil_currency'];
        if (!$currency instanceof Currency) {
            $currency = MoneyConverter::currency($currency);
        }

        return MoneyConverter::money($amount, $currency);
    }
}
