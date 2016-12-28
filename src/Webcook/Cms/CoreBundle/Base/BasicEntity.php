<?php

namespace Webcook\Cms\CoreBundle\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
abstract class BasicEntity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Version
     * @ORM\Column(type="integer")
     */
    protected $version;

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return get_called_class();
    }

    /**
     * Gets the value of version.
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Convert current object into array.
     *
     * @return Array
     */
    public function toArray()
    {
        $reflection = new \ReflectionClass($this);
        $vars = array_keys($reflection->getdefaultProperties());

        $array = array();
        foreach ($vars as $attribute) {
            $getter = 'get'.ucfirst($attribute);
            if (method_exists($this, $getter)) {
                if (!is_object($this->$getter())) {
                    $array[$attribute] = $this->$getter();
                } elseif (is_object($this->$getter())) {
                    if (method_exists($this->$getter(), 'getId')) {
                        $array[$attribute] = $this->$getter()->getId();
                    } elseif ($this->$getter() instanceof \DateTime) {
                        $array[$attribute] = $this->$getter()->format('d-m-Y');
                    } elseif ($this->$getter() instanceof \Doctrine\Common\Collections\Collection) {
                        $collection = array();
                        foreach ($this->$getter() as $item) {
                            $collection[] = $item->getId();
                        }
                        $array[$attribute] = $collection;
                    }
                }
            }
        }

        return $array;
    }
}
