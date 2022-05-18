<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Phil\MoneyBundle\Form\DataTransformer\CurrencyToArrayTransformer;

/**
 * Formtype for the Currency object.
 */
class CurrencyType extends AbstractType
{
    /**
     * @param string[] $currencyCodeList currency codes array eg ["USD", "EUR"]
     */
    public function __construct(
        protected array $currencyCodeList,
        /**
         * currency code like "USD", "EUR".
         */
        protected string $referenceCurrencyCode
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedAssignment, MixedArgument, MixedArgumentTypeCoercion, MixedArrayOffset
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choiceList = [];
        foreach ($options['currency_choices'] as $currencyCode) {
            $choiceList[$currencyCode] = $currencyCode;
        }

        $builder->add('phil_name', ChoiceType::class, array_merge([
            'choices' => $choiceList,
            'preferred_choices' => [$options['reference_currency']],
        ], $options['currency_options']));

        $builder->addModelTransformer(new CurrencyToArrayTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['reference_currency', 'currency_choices']);
        $resolver->setDefaults([
            'reference_currency' => $this->referenceCurrencyCode,
            'currency_choices' => $this->currencyCodeList,
            'currency_options' => [],
        ]);
        $resolver->setAllowedTypes('reference_currency', 'string');
        $resolver->setAllowedTypes('currency_choices', 'array');
        $resolver->setAllowedValues('reference_currency', $this->currencyCodeList);
        $resolver->setAllowedTypes('currency_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'phil_currency';
    }
}
