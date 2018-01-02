<?php
/**
 * Created by Philippe Le Van.
 * Date: 01/07/13
 */

namespace Phil\MoneyBundle\Tests\Form\Type;

use Money\Currency;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Phil\MoneyBundle\Form\Type\CurrencyType;
use Symfony\Component\Form\Test\TypeTestCase;
use Money\Money;
use Phil\MoneyBundle\Form\Type\SimpleMoneyType;
use Phil\MoneyBundle\Pair\PairManager;

class SimpleMoneyTypeTest
    extends TypeTestCase
{
    private $pairManager;
    private $simpleMoneyTypeClass = 'Phil\MoneyBundle\Form\Type\SimpleMoneyType';

    public function testBindValid()
    {
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, array());
        $form->submit(array(
            "phil_amount" => '12'
        ));
        $this->assertEquals(Money::EUR(1200), $form->getData());
    }
    public function testBindValidDecimals()
    {
        \Locale::setDefault("fr_FR");
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, array());
        $form->submit(array(
            "phil_amount" => '1,2'
        ));
        $this->assertEquals(Money::EUR(1200), $form->getData());
    }

    public function testBindDecimalValid()
    {
        \Locale::setDefault("fr_FR");
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, array());
        $form->submit(array(
            "phil_amount" => '12,5'
        ));
        $this->assertEquals(Money::EUR(1250), $form->getData());
    }

    public function testGreaterThan1000Valid()
    {
        \Locale::setDefault("fr_FR");
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, array());
        $form->submit(array(
            "phil_amount" => '1 252,5'
        ));
        $this->assertEquals(Money::EUR(125250), $form->getData());
    }

    public function testSetData()
    {
        \Locale::setDefault("fr_FR");
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, array());
        $form->setData(Money::EUR(120));
        $formView = $form->createView();

        $this->assertEquals("1,20", $formView->children["phil_amount"]->vars["value"]);
    }

    protected function getExtensions()
    {
        //This is probably not ideal, but I'm not sure how to set up the pair manager
        // with different decimals for different tests in Symfony 3.0
        $decimals = 2;
        $currencies = array('EUR', 'USD');
        $referenceCurrency = 'EUR';

        if($this->getName() === "testBindValidDecimals")
            $decimals = 3;

        $this->pairManager = $this->getMockBuilder('Phil\MoneyBundle\Pair\PairManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->pairManager->expects($this->any())
            ->method('getReferenceCurrencyCode')
            ->will($this->returnValue("EUR"));

        return array(
            new PreloadedExtension(
                array(new SimpleMoneyType($decimals, $currencies, $referenceCurrency)), array()
            )
        );
    }

    public function testOverrideCurrency()
    {
        \Locale::setDefault("fr_FR");
        $form = $this->factory->create($this->simpleMoneyTypeClass, null, ["currency" => "USD"]);
        $form->submit(array(
            "phil_amount" => '1 252,5'
        ));
        $this->assertEquals(Money::USD(125250), $form->getData());
    }

}
