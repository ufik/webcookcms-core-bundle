<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;

/**
 * Content provider settings abstract entity.
 *
 * @ORM\MappedSuperclass
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
}
