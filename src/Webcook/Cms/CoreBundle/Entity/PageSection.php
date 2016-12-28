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
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    public function setContentProvider($contentProvider)
    {
        $this->contentProvider = $contentProvider;

        return $this;
    }

    public function getContentProvider()
    {
        return $this->contentProvider;
    }
}
