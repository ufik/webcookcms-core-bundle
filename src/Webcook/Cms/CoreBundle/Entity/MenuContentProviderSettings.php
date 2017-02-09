<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Content provider settings abstract entity.
 *
 * @ApiResource(
 *  itemOperations={
 *     "get"={"method"="GET"},
 *     "put"={"method"="PUT"},
 *     "delete"={"method"="DELETE"},
 *     "menu_content_get"={"route_name"="menu_content"}
 * })
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
