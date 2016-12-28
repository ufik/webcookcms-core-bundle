<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

class LanguageControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetLanguages()
    {
        $this->loadData();
        $this->createTestClient();
        $this->client->request('GET', '/api/languages');

        $languages = $this->client->getResponse()->getContent();

        $data = json_decode($languages, true);
        $this->assertCount(3, $data);
    }

    public function testGetlanguage()
    {
        $this->loadData();
        $this->createTestClient();
        $this->client->request('GET', '/api/languages/1');
        $language = $this->client->getResponse()->getContent();

        $data = json_decode($language, true);

		$this->assertEquals(1, $data['id']);
		$this->assertEquals(1, $data['version']);
		$this->assertEquals('Čeština', $data['title']);
		$this->assertEquals('cs', $data['abbr']);
    }

    public function testPost()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/languages',
            array(
                'language' => array(
                    'title' => 'Test lang',
                    'abbr' => 'tl',
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $languages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\language')->findAll();

        $this->assertCount(4, $languages);
        $this->assertEquals('Test lang', $languages[3]->getTitle());
        $this->assertEquals('tl', $languages[3]->getAbbr());
    }

    public function testPut()
    {
        $this->loadData();
        $this->createTestClient();

        $this->client->request('GET', '/api/languages/2'); // save version into session
        $crawler = $this->client->request(
            'PUT',
            '/api/languages/2',
            array(
                'language' => array(
                    'title' => 'English updated',
                    'abbr' => 'en',
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $language = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\language')->find(2);

        $this->assertEquals('English updated', $language->getTitle());
        $this->assertEquals('en', $language->getAbbr());
    }

    public function testDelete()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request('DELETE', '/api/languages/2');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $Languages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\language')->findAll();

        $this->assertCount(2, $Languages);
    }

    public function testWrongPost()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/languages',
            array(
                'language' => array(
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
            '/api/languages/4',
            array(
                'language' => array(
                    'title' => 'Spanish',
                    'abbr' => 'es',
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $languages = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\language')->findAll();

        $this->assertCount(4, $languages);
        $this->assertEquals('Spanish', $languages[3]->getTitle());
        $this->assertEquals('es', $languages[3]->getAbbr());
    }

    public function testWrongPut()
    {
        $this->loadData();
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/languages/1',
            array(
                'language' => array(
                    'name' => 'Tester missing Language field',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    private function loadData()
    {
        $this->loadFixtures(array(
            'Webcook\Cms\CoreBundle\DataFixtures\ORM\LoadLanguageData'
        ));
    }
}
