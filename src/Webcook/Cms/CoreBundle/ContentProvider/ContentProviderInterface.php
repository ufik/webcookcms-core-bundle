<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook Communications
 */

namespace Webcook\Cms\CoreBundle\ContentProvider;

use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\Section;

interface ContentProviderInterface
{
    /**
     * Get string content.
     *
     * @param Page    $page    
     * @param Section $section 
     *
     * @return String Rendered content.
     */
    public function getContent(Page $page, Section $section): string;
}
