<?php
namespace Phil\MoneyBundle\Tests\Money;

use Money\Money;
use Phil\MoneyBundle\Money\MoneyManager;
use Phil\MoneyBundle\MoneyException;

/**
 * @group moneymanager
 */
class MoneyManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  MoneyManager */
    protected $manager;

    public function setUp()
    {
        $this->manager = new MoneyManager("EUR", 2);
    }

    public function tearDown()
    {
    }

    public function testCreateMoneyFromFloat()
    {
        $money = $this->manager->createMoneyFromFloat(2.5);
        $this->assertEquals("EUR", $money->getCurrency()->getCode());
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.5, 'USD');
        $this->assertEquals("USD", $money->getCurrency()->getCode());
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.49999999999999);
        $this->assertEquals(250, $money->getAmount());

        $money = $this->manager->createMoneyFromFloat(2.529999999999);
        $this->assertEquals(253, $money->getAmount());
    }

}