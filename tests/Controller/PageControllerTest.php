<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class PageControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetPages()
    {
        $this->createTestClient();
        $this->client->request('GET', '/api/pages.json');

        $pages = $this->client->getResponse()->getContent();

        $data = json_decode($pages, true);
        $this->assertCount(7, $data);
    }

    public function testGetPage()
    {
        $this->createTestClient();
        $this->client->request('GET', '/api/pages/1.json');
        $page = $this->client->getResponse()->getContent();

        $data = json_decode($page, true);

        $this->assertEquals(1, $data['id']);
        $this->assertEquals(1, $data['version']);
        $this->assertEquals('Main', $data['title']);
        $this->assertEquals('This is h1', $data['h1']);
        $this->assertEquals('description', $data['description']);
        $this->assertEquals('key, words', $data['keywords']);
        $this->assertEquals('default', $data['layout']);
        //$this->assertCount(1, $data['sections']);
    }

    public function testPost()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/pages.json',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
            ),
            json_encode(array(
                'title' => 'New menu',
                'layout' => 'default',
                'language' => '/api/languages/1',
                'h1' => 'h1 title',
                'description' => 'desc',
                'keywords' => 'key, words'
            ))
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(8, $pages);
        $this->assertEquals('New menu', $pages[7]->getTitle());
        $this->assertEquals('h1 title', $pages[7]->getH1());
        $this->assertEquals('desc', $pages[7]->getDescription());
        $this->assertEquals('key, words', $pages[7]->getKeywords());
    }

    public function testPut()
    {
        $this->createTestClient();

        $this->client->request('GET', '/api/pages/2.json'); // save version into session
        $crawler = $this->client->request(
            'PUT',
            '/api/pages/2.json',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
            ),
            json_encode(
                array(
                    'title' => 'Updated menu',
                    'layout' => 'default',
                    'language' => '/api/languages/1',
                    'h1' => 'Updated h1',
                    'description' => 'Updated description',
                    'keywords' => 'Updated keywords'
                )
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(2);

        $this->assertEquals('Updated menu', $page->getTitle());
        $this->assertEquals('Updated h1', $page->getH1());
        $this->assertEquals('Updated description', $page->getDescription());
        $this->assertEquals('Updated keywords', $page->getKeywords());
    }

    public function testDelete()
    {
        $this->createTestClient();

        $crawler = $this->client->request('DELETE', '/api/pages/2.json');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(5, $pages);
    }

    public function testWrongPost()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/pages.json',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
            ),
            json_encode(array(
                'n' => 'Tester',
            ))
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutNonExisting()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/pages/8.json',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
            ),
            json_encode(array(
                'title' => 'New menu',
                'layout' => 'default',
                'language' => 1,
                'h1' => 'Updated h1',
                'description' => 'Updated description',
                'keywords' => 'Updated keywords'
            ))
        );

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testWrongPut()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/pages/2.json',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
            ),
            json_encode(array(
                'name' => 'Tester missing Page field',
            ))
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
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
}
