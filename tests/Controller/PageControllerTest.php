<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class PageControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetPages()
    {
        $this->createTestClient();
        $this->client->request('GET', '/api/pages');

        $pages = $this->client->getResponse()->getContent();

        $data = json_decode($pages, true);
        $this->assertCount(7, $data);
    }

    public function testGetPage()
    {
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
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/pages',
            array(
                'page' => array(
                    'title' => 'New menu',
                    'layout' => 'default',
                    'language' => 1
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(8, $pages);
        $this->assertEquals('New menu', $pages[7]->getTitle());
    }

    public function testPut()
    {
        $this->createTestClient();

        $this->client->request('GET', '/api/pages/2'); // save version into session
        $crawler = $this->client->request(
            'PUT',
            '/api/pages/2',
            array(
                'page' => array(
                    'title' => 'Updated menu',
                    'layout' => 'default',
                    'language' => 1
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(2);

        $this->assertEquals('Updated menu', $page->getTitle());
    }

    public function testPutPageSectionOrder()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/page-section/order/1',
            array(
                'pageSection' => array(
                    'order' => 4
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\PageSection')->find(1);

        $this->assertEquals(4, $page->getOrder());
    }

    public function testDelete()
    {
        $this->createTestClient();

        $crawler = $this->client->request('DELETE', '/api/pages/2');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(5, $pages);
    }

    public function testWrongPost()
    {
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
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/pages/8',
            array(
                'page' => array(
                    'title' => 'New menu',
                    'layout' => 'default',
                    'language' => 1
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(8, $pages);
        $this->assertEquals('New menu', $pages[7]->getTitle());
    }

    public function testWrongPut()
    {
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
}
