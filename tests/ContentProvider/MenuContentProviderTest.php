<?php

namespace Webcook\Cms\CoreBundle\ContentProvider;

use Webcook\Cms\CoreBundle\ContentProvider\MenuContentProvider;

class MenuContentProviderTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testMenuContentProviderContent()
    {
        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(1);
        $section = $page->getSections()[0]->getSection();

        $menuContentProvider = $this->container->get('webcookcms.core.menu_content_provider');
        $content = $menuContentProvider->getContent($page, $section);
        
        $this->assertContains('/en/home', $content);
        $this->assertContains('/en/contact', $content);
    }
}
