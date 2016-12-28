<?php

/**
 * This file is part of Webcook security bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Money\Currency;

/**
 * @ORM\Embeddable
 */
class DoctrineMoney
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $amount;

    /**
     * @ORM\Column(type="string", length=3, nullable=true, options={"fixed" = true})
     */
    public $currency;

    /**
     * @var Money money object
     */
    private $money;

    public function __construct(Money $money)
    {
        $this->setMoney($money);
    }

    /**
     * Gets the value of amount.
     *
     * @return null|Money
     */
    public function getMoney()
    {
        if (!$this->currency) {
            return;
        }

        if (!$this->amount) {
            return new Money(0, new Currency($this->currency));
        }

        return new Money($this->amount, new Currency($this->currency));
    }

    /**
     * Sets the value of amount.
     *
     *
     * @return self
     */
    public function setMoney(Money $money)
    {
        $this->amount = $money->getAmount();
        $this->currency = $money->getCurrency()->getName();
        $this->money = $money;

        return $this;
    }

    /**
     * Gets the value of amount.
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets the value of amount.
     *
     * @param mixed $amount the amount
     *
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Gets the value of currency.
     *
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets the value of currency.
     *
     * @param mixed $currency the currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }
}
