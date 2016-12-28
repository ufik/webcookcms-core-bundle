<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class PageControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetPages()
    {
        $this->loadData();
        $this->createTestClient();
        $this->client->request('GET', '/api/pages');

        $pages = $this->client->getResponse()->getContent();

        $data = json_decode($pages, true);
        $this->assertCount(3, $data);
    }

    public function testGetPage()
    {
        $this->loadData();
        $this->createTestClient();
        $this->client->request('GET', '/api/pages/1');
        $page = $this->client->getResponse()->getContent();

        $data = json_decode($page, true);

		$this->assertEquals(1, $data['id']);
		$this->assertEquals(1, $data['version']);
		$this->assertEquals('Main', $data['title']);
		$this->assertEquals('default', $data['layout']);
        $this->assertCount(1, $data['sections']);
    }

    public function testPost()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/pages',
            array(
                'page' => array(
                    'title' => 'New menu',
                    'layout' => 'default',
                    'sections' => array(array(
                        'section' => 1,
                        'contentProvider' => 1
                    ))
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(4, $pages);
        $this->assertEquals('New menu', $pages[3]->getTitle());
        $this->assertCount(1, $pages[3]->getSections());
    }

    public function testPut()
    {
        $this->loadData();
        $this->createTestClient();

        $this->client->request('GET', '/api/pages/2'); // save version into session
        $crawler = $this->client->request(
            'PUT',
            '/api/pages/2',
            array(
                'page' => array(
                    'title' => 'Updated menu',
                    'layout' => 'default',
                    'sections' => array(array(
                        'section' => 1,
                        'contentProvider' => 1
                    ))
                ),
            )            
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(2);

        $this->assertEquals('Updated menu', $page->getTitle());
        $this->assertCount(1, $page->getSections());
    }

    public function testDelete()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request('DELETE', '/api/pages/2');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(2, $pages);
    }

    public function testWrongPost()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/pages',
            array(
                'page' => array(
                    'n' => 'Tester',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutNonExisting()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/pages/4',
            array(
                'page' => array(
                    'title' => 'New menu',
                    'layout' => 'default',
                    'sections' => array(array(
                        'section' => 1,
                        'contentProvider' => 1
                    ))
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(4, $pages);
        $this->assertEquals('New menu', $pages[3]->getTitle());
        $this->assertCount(1, $pages[3]->getSections());
    }

    public function testWrongPut()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/pages/1',
            array(
                'page' => array(
                    'name' => 'Tester missing Page field',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
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
