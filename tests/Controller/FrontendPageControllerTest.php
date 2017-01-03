<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class FrontendPageControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{

    public function setUp($clearCache = false)
    {
        parent::setUp(true);
    }

    public function testGetPage()
    {
        $this->loadData();
        $this->createTestClient();

        $this->client->request('GET', '/en/home');

        $html = $this->client->getResponse()->getContent();

        $this->assertContains('<a href="/en/home">Home</a>', $html);
        $this->assertContains('<a href="/en/contact">Contact</a>', $html);
    }

    public function testGetDefaultLanguagePage()
    {
        $this->loadData();
        $this->createTestClient();

        $this->client->request('GET', '/uvod');

        $html = $this->client->getResponse()->getContent();

        $this->assertContains('<a href="/uvod">Uvod</a>', $html);
    }

    private function loadData()
    {
        $this->loadFixtures(array(
            'Webcook\Cms\CoreBundle\DataFixtures\ORM\LoadContentProviderData'
        ));
    }
}
