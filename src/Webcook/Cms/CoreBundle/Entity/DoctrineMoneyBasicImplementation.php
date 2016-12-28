<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;

/**
 * Just for unit test purpose.
 *
 * @ORM\Entity
 * @ORM\Table(name="TestDoctrineMoney")
 */
class DoctrineMoneyBasicImplementation extends BasicEntity
{
    /**
     * @ORM\Embedded(class="Webcook\Cms\CoreBundle\Entity\DoctrineMoney")
     */
    private $revenue;

    /**
     * Gets the value of revenue.
     *
     * @return mixed
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * Sets the value of revenue.
     *
     * @param mixed $revenue the revenue
     *
     * @return self
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }
}
