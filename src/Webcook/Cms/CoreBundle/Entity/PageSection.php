<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page section entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="PageSection")
 */
class PageSection
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="sections")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="ContentProvider")
     * @ORM\JoinColumn(name="content_provider_id", referencedColumnName="id")
     */
    private $contentProvider;

    /**
     * @ORM\Column(name="content_order", type="integer")
     */
    private $order;

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Gets the value of page.
     *
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Sets the value of page.
     *
     * @param mixed $page the page
     *
     * @return self
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Gets the value of section.
     *
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Sets the value of section.
     *
     * @param mixed $section the section
     *
     * @return self
     */
    public function setSection($section)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Gets the value of contentProvider.
     *
     * @return mixed
     */
    public function getContentProvider()
    {
        return $this->contentProvider;
    }

    /**
     * Sets the value of contentProvider.
     *
     * @param mixed $contentProvider the content provider
     *
     * @return self
     */
    public function setContentProvider($contentProvider)
    {
        $this->contentProvider = $contentProvider;

        return $this;
    }
}
