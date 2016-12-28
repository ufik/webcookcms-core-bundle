<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;

/**
 * Content provider entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="Section")
 */
class Section extends BasicEntity
{
    /**
     * @ORM\Column(name="name", type="string", length=55)
     */
    private $name;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
