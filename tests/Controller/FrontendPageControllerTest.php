<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class FrontendPageControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetPage()
    {
        $this->loadData();
        $this->createTestClient();

        $this->client->request('GET', '/cs/main/home');

        $html = $this->client->getResponse()->getContent();

        $this->assertContains('<a href="/cs/main">Main</a>', $html);
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
