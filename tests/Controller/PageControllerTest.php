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
        $this->assertEquals('Main', $data[0]['title']);
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
        $this->jsonRequest(
            'POST',
            '/api/pages',
            array(
                'title' => 'New menu',
                'layout' => 'default',
                'language' => '/api/languages/1',
                'h1' => 'h1 title',
                'description' => 'desc',
                'keywords' => 'key, words'
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $pages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();

        $this->assertCount(8, $pages);
        $this->assertEquals('New menu', $pages[7]->getTitle());
        $this->assertEquals('default', $pages[7]->getLayout());
        $this->assertEquals('cs', $pages[7]->getLanguage()->getLocale());
        $this->assertEquals('h1 title', $pages[7]->getH1());
        $this->assertEquals('desc', $pages[7]->getDescription());
        $this->assertEquals('key, words', $pages[7]->getKeywords());
    }

    public function testPut()
    {
        $this->jsonRequest(
            'PUT',
            '/api/pages/2',
            array(
                'title' => 'Updated menu',
                'layout' => 'default',
                'language' => '/api/languages/1',
                'h1' => 'Updated h1',
                'description' => 'Updated description',
                'keywords' => 'Updated keywords'
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

        $crawler = $this->jsonRequest(
            'POST',
            '/api/pages',
            array(
                'n' => 'Tester',
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutNonExisting()
    {
        $this->createTestClient();

        $crawler = $this->jsonRequest(
            'PUT',
            '/api/pages/8',
            array(
                'title' => 'New menu',
                'layout' => 'default',
                'language' => 1,
                'h1' => 'Updated h1',
                'description' => 'Updated description',
                'keywords' => 'Updated keywords'
            )
        );

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testWrongPut()
    {
        $this->jsonRequest(
            'PUT',
            '/api/pages/2',
            array(
                'name' => 'Tester missing Page field',
            )
        );

        $this->markTestSkipped('Api platform sending 200 instead of 400.');
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutPageSectionOrder()
    {
        $this->jsonRequest(
            'PUT',
            '/api/page-section/1/order',
            array(
                'order' => 4
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\PageSection')->find(1);

        $this->assertEquals(4, $page->getOrder());
    }

    public function testPutNonExistingPageSectionOrder()
    {
        $this->jsonRequest(
            'PUT',
            '/api/page-section/99/order',
            array(
                'order' => 4
            )
        );

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testPutWrongPageSectionOrder()
    {
        $this->jsonRequest(
            'PUT',
            '/api/page-section/1/order',
            array(
                'order' => 'wrong param'
            )
        );

        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}
