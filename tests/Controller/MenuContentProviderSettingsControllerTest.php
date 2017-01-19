<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class MenuContentProviderSettingsControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetSettings()
    {
        $this->createTestClient();
        $this->client->request('GET', '/tapi/content-providers/menu/settings/1/1');

        $settings = $this->client->getResponse()->getContent();

        $data = json_decode($settings, true);
        $this->assertEquals('Main', $data['page']['title']);
        $this->assertEquals('Menu', $data['section']['name']);
        $this->assertEquals('Main', $data['parent']['title']);
    }

    public function testGetNonExistingSettings()
    {
        $this->createTestClient();
        $this->client->request('GET', '/tapi/content-providers/menu/settings/1/2');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPost()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/tapi/content-providers/menu/settings',
            array(
                'menu_content_provider_settings' => array(
                    'page' => 1,
                    'section' => 1,
                    'parent' => 1
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->findAll();

        $this->assertCount(4, $settings);
        $this->assertEquals('Main', $settings[3]->getPage()->getTitle());
        $this->assertEquals('Menu', $settings[3]->getSection()->getName());
        $this->assertEquals('Main', $settings[3]->getParent()->getTitle());
    }

    public function testPut()
    {
        $this->createTestClient();

        $this->client->request('GET', '/tapi/content-providers/menu/settings/2'); // save version into session
        $crawler = $this->client->request(
            'PUT',
            '/tapi/content-providers/menu/settings/2',
            array(
                'menu_content_provider_settings' => array(
                    'page' => 5,
                    'section' => 2,
                    'parent' => 1
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->find(2);

        $this->assertEquals('Footer', $settings->getPage()->getTitle());
        $this->assertEquals('Content', $settings->getSection()->getName());
        $this->assertEquals('Main', $settings->getParent()->getTitle());
    }

    public function testDelete()
    {
        $this->createTestClient();

        $crawler = $this->client->request('DELETE', '/tapi/content-providers/menu/settings/2');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $Languages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->findAll();

        $this->assertCount(2, $Languages);
    }

    public function testWrongPost()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/tapi/content-providers/menu/settings',
            array(
                'menu_content_provider_settings' => array(
                    'n' => 'Tester',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutNonExisting()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/tapi/content-providers/menu/settings/4',
            array(
                'menu_content_provider_settings' => array(
                    'page' => 5,
                    'section' => 2,
                    'parent' => 1
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->find(4);

        $this->assertEquals('Footer', $settings->getPage()->getTitle());
        $this->assertEquals('Content', $settings->getSection()->getName());
        $this->assertEquals('Main', $settings->getParent()->getTitle());
    }

    public function testWrongPut()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/tapi/content-providers/menu/settings/1',
            array(
                'menu_content_provider_settings' => array(
                    'name' => 'Test missing field',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}
