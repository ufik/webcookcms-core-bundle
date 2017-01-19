<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Webcook\Cms\CoreBundle\Base\BasicEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\Tree(type="nested")
 * @ApiResource(attributes={
 *     "denormalization_context"={"groups"={"write"}}
 * })
 * @ORM\Table(name="Page")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * TODO: redirects, inheritance from parents
 */
class Page extends BasicEntity
{
    /**
     * @ORM\Column(length=64)
     * @Groups({"write"})
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(length=64, nullable=true)
     * @Groups({"write"})
     */
    private $h1;

    /**
     * @ORM\Column(length=64, nullable=true)
     * @Groups({"write"})
     */
    private $keywords;

    /**
     * @ORM\Column(length=64, nullable=true)
     * @Groups({"write"})
     */
    private $description;

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
     * @MaxDepth(2)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @MaxDepth(2)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     * @MaxDepth(2)
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Webcook\Cms\I18nBundle\Entity\Language")
     * @Groups({"write"})
     * @Assert\NotBlank
     */
    private $language;

    /**
     * @ORM\Column(length=2)
     */
    private $languageAbbr;

    /**
     * @ORM\Column(length=64)
     * @Groups({"write"})
     * @Assert\NotBlank
     */
    private $layout;

    /**
     * @ORM\OneToMany(targetEntity="PageSection", mappedBy="page", cascade={"persist"}, fetch="EAGER")
     * @MaxDepth(5)
     * @ApiProperty(readable = false)
     */
    private $sections;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
    }

    /** @ORM\PrePersist */
    public function setLanguageAbbr()
    {
        $this->languageAbbr = $this->language->getLocale();
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

    public function setLanguage($language)
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

        // FIXME, need to merge sections and lookup into parent for single items!
        if (!is_null($this->parent)) {
            $this->sections = new ArrayCollection(array_merge($this->sections->toArray(), $this->parent->getSections()->toArray()));
        }

        return $this->sections;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getRouteName()
    {
        return $this->getLanguage()->getLocale().'_'.str_replace('/', '_', $this->slug);
    }

    public function getPath()
    {
        $path = (!$this->getLanguage()->isDefault() ? $this->getLanguage()->getLocale() : '') . $this->getSlug();

        return str_replace($this->getRoot()->getSlug(), '', $path);
    }

    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Gets the value of h1.
     *
     * @return mixed
     */
    public function getH1()
    {
        if (empty($this->h1)) {
            return $this->title;
        }

        return $this->h1;
    }

    /**
     * Sets the value of h1.
     *
     * @param mixed $h1 the h1
     *
     * @return self
     */
    public function setH1($h1)
    {
        $this->h1 = $h1;

        return $this;
    }

    /**
     * Gets the value of keywords.
     *
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Sets the value of keywords.
     *
     * @param mixed $keywords the keywords
     *
     * @return self
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
