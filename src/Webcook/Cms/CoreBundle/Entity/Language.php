<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CoreBundle\Base\BasicEntity;

/**
 * Just for unit test purpose.
 *
 * @ORM\Entity
 * @ORM\Table(name="Language")
 */
class Language extends BasicEntity
{
    /**
     * @ORM\Column(name="title", type="string", length=55)
     */
    private $title;

    /**
     * @ORM\Column(name="abbr", type="string", length=2)
     */
    private $abbr;

    /**
     * Set title.
     *
     * @param String $title Title of a language.
     *
     * @return Language Returns self.
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return String Title of a language.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set abbreviation of a language.
     *
     * @param String $abbr Abbreviation of a language.
     *
     * @return Language Returns self.
     */
    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;

        return $this;
    }

    /**
     * Get abbreviation.
     *
     * @return String Returns abbreavtion of language.
     */
    public function getAbbr()
    {
        return $this->abbr;
    }
}
