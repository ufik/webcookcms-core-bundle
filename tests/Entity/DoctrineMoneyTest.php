<?php

namespace Webcook\Cms\CoreBundle\Controller;

use Webcook\Cms\CoreBundle\Entity\DoctrineMoneyBasicImplementation;
use Webcook\Cms\CoreBundle\Entity\DoctrineMoney;
use Money\Money;
use Money\Currency;

class DoctrineMoneyTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testDoctrineMoneyPersist()
    {
        $doctrineMoney = new DoctrineMoneyBasicImplementation();
        $doctrineMoney->setRevenue(new Money(100, new Currency('EUR')));

        $this->em->persist($doctrineMoney);
        $this->em->flush();

        $doctrineMoney = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\DoctrineMoneyBasicImplementation')->find(1);
        $this->assertEquals(100, $doctrineMoney->getRevenue()->getAmount());
        $this->assertEquals('EUR', $doctrineMoney->getRevenue()->getCurrency());
    }

    public function testDoctrineMoneyEntity()
    {
        $money = new Money(999, new Currency('EUR'));
        $currency = new Currency('USD');
        $doctrineMoney = new DoctrineMoney($money);
        $doctrineMoney->setCurrency('USD');
        $doctrineMoney->setAmount(1000);

        $this->assertEquals(1000, $doctrineMoney->getAmount());
        $this->assertEquals('USD', $doctrineMoney->getCurrency());
        $money = new Money(1000, new Currency('USD'));
        $this->assertEquals($money, $doctrineMoney->getMoney());
    }
}
