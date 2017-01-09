<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;

/**
 * Content provider settings abstract entity.
 *
 * @ORM\Entity()
 */
class MenuContentProviderSettings extends ContentProviderSettings
{
    const TAG = 'webcookcms.core.menu_content_provider';

    /**
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=false)
     */
    private $parent;

    /** @ORM\Column(type="boolean") */
    private $directChildren = false;

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function setDirectChildren(Bool $directChildren)
    {
        $this->directChildren=$directChildren;

        return $this;
    }

    public function getDirectChildren()
    {
        return $this->directChildren;
    }
}
