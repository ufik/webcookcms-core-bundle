<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Webcook\Cms\CoreBundle\Base\BasicEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Test.
 *
 * @ORM\Entity
 * @ORM\Table(name="TestEntity")
 */
class TestEntity extends BasicEntity
{
    /**
     * @ORM\Column(name="variable", length=32)
     */
    private $variable;
    /**
     * @ORM\Column(name="date", type="date", length=32, nullable=true)
     */
    private $date;
    /**
     * @ORM\Column(name="collection", type="array", length=32, nullable=true)
     */
    private $collection;
    /**
     * @ORM\Column(name="object", type="object", length=32, nullable=true)
     */
    private $object;

    public function getId()
    {
        return $this->id;
    }

    public function getVariable()
    {
        return $this->variable;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function addToCollection($object)
    {
        $this->collection->add($object);

        return $this;
    }

    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setVariable($variable = 'variable')
    {
        $this->variable = $variable;

        return $this;
    }
}
