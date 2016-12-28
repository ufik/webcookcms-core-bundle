<?php

namespace Webcook\Cms\CoreBundle\ContentProvider;

use Webcook\Cms\CoreBundle\ContentProvider\MenuContentProvider;

class MenuContentProviderTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testMenuContentProviderContent()
    {
        $this->loadData();

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(1);
        $section = $page->getSections()[0]->getSection();

        $menuContentProvider = $this->container->get('webcookcms.common.menu_content_provider');
        $content = $menuContentProvider->getContent($page, $section);
        
        $this->assertContains('/main', $content);
        $this->assertContains('/main/home', $content);
        $this->assertContains('/footer', $content);
    }

    private function loadData()
    {
        $this->loadFixtures(array(
            'Webcook\Cms\CoreBundle\DataFixtures\ORM\LoadContentProviderData',
            'Webcook\Cms\CoreBundle\DataFixtures\ORM\LoadSectionData',
            'Webcook\Cms\CoreBundle\DataFixtures\ORM\LoadLanguageData',
            'Webcook\Cms\CoreBundle\DataFixtures\ORM\LoadPageData'
        ));
    }
}
