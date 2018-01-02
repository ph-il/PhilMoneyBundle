<?php

namespace Phil\MoneyBundle\Tests\Pair\Storage;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Phil\MoneyBundle\Pair\RatioProviderInterface;

/**
 * This class can be used to easily test your custom ratio providers.
 *
 * @author Hugues Maignol <hugues.maignol@kitpages.fr>
 */
abstract class AbstractRatioProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The currently tested RatioProvider.
     *
     * @var RatioProviderInterface
     */
    protected $ratioProvider;

    public function setUp()
    {
        $this->ratioProvider = $this->getRatioProvider();
    }

    public function testRatioFetching()
    {
        foreach ($this->getRatiosToTest() as $testParameters) {
            $ratio = $this->ratioProvider->fetchRatio($testParameters['reference'], $testParameters['currency']);
            $this->assertInternalType('float', $ratio, 'The fetched ratio must be a float');
            $this->assertLessThan(
                $testParameters['ratio_max'],
                $ratio,
                'The ratio is too high, are wee in deep economical crisis ?'
            );
            $this->assertGreaterThan(
                $testParameters['ratio_min'],
                $ratio,
                'The ratio is too low'
            );
        }
    }

    public function testExceptionForUnknownCurrency()
    {
        $this->setExpectedException('Phil\MoneyBundle\MoneyException');
        $this->ratioProvider->fetchRatio('ZZZ', 'USD');
    }

    /**
     * Returns the instanciated RatioProvider service that will be tested.
     *
     * @return RatioProviderInterface
     */
    abstract protected function getRatioProvider();

    /**
     * Each array value returned is an array with the keys :
     *  - reference : The base currency for the ratio
     *  - currency : The currency for which we want the ratio
     *  - ratio_min : The minimum ratio value considered valid
     *  - ratio_max : The maximum ratio value considered valid.
     *
     * @return array[]
     */
    protected function getRatiosToTest()
    {
        return array(
            array(
                'reference' => 'EUR',
                'currency' => 'USD',
                'ratio_min' => 0.3,
                'ratio_max' => 3,
            ),
            array(
                'reference' => 'GBP',
                'currency' => 'EUR',
                'ratio_min' => 0.3,
                'ratio_max' => 3,
            ),
        );
    }
}
