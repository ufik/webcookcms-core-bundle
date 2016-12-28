<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Webcook\Cms\CoreBundle\Base\BasicEntity;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="Page")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\HasLifecycleCallbacks
 * TODO: redirects, SEO features, inheritance from parents
 */
class Page Extends BasicEntity
{
    /**
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/"),
     *          @Gedmo\SlugHandlerOption(name="urilize", value=true)
     *      })
     * }, fields={"title"}, unique_base="language")
     * @Doctrine\ORM\Mapping\Column(length=128)
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Page")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Language")
     */
    private $language;

    /**
     * @ORM\Column(length=2)
     */
    private $languageAbbr;

    /**
     * @ORM\Column(length=64)
     */
    private $layout;

    /**
     * @ORM\OneToMany(targetEntity="PageSection", mappedBy="page", cascade={"persist"}, fetch="EAGER")
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    /** @ORM\PrePersist */
    public function setLanguageAbbr()
    {
        $this->languageAbbr = $this->language->getAbbr();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setParent(Page $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setLanguage(Language $language)
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getSections($inherit = false)
    {
        if ($this->sections->isEmpty() && $this->parent && $inherit) {
            return $this->parent->getSections(true);
        }

        return $this->sections;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getRouteName()
    {
        return $this->getLanguage()->getAbbr().'_'.str_replace('/', '_', $this->slug);
    }

    public function getPath()
    {
        $path = (!$this->getLanguage()->isDefault() ? $this->getLanguage()->getAbbr() : '') . $this->getSlug();

        return str_replace($this->getRoot()->getSlug(), '', $path);
    }

    public function getLvl()
    {
        return $this->lvl;
    }
}
