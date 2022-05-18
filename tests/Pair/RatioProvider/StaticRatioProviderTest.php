<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests\Pair\RatioProvider;

use Phil\MoneyBundle\Pair\RatioProvider\StaticRatioProvider;
use Phil\MoneyBundle\Pair\RatioProviderInterface;

class StaticRatioProviderTest extends AbstractRatioProviderTest
{
    protected function getRatioProvider(): RatioProviderInterface
    {
        $provider = new StaticRatioProvider();
        $ratios = $this->getRatiosToTest();
        foreach ($ratios as $idx => $ratioData) {
            $ratio = $this->randomRatio($ratioData['ratio_min'], $ratioData['ratio_max'], $idx);
            $provider->setRatio(
                $ratioData['reference'],
                $ratioData['currency'],
                $ratio
            );
        }

        return $provider;
    }
}
