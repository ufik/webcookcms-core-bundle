<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;
use Webcook\Cms\CoreBundle\Entity\PageSection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Content provider settings abstract entity.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class ContentProviderSettings extends BasicEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id", nullable=false)
     */
    private $section;

    /**
     * Holder for order, will pass it to PageSection.
     */
    private $order = 0;

    /**
     * @ORM\PrePersist
     */
    public function savePageSection(LifecycleEventArgs $eventArgs)
    {
        $em              = $eventArgs->getEntityManager();
        $contentProvider = $em->getRepository('Webcook\Cms\CoreBundle\Entity\ContentProvider')->findOneByName(get_called_class()::TAG);

        $pageSection = new PageSection();
        $pageSection->setPage($this->page);
        $pageSection->setSection($this->section);
        $pageSection->setOrder($this->order);
        $pageSection->setContentProvider($contentProvider);

        $em->persist($pageSection);
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    public function getSection()
    {
        return $this->section;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the value of order.
     *
     * @param mixed $order the order
     *
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
