<?php

namespace Webcook\Cms\CoreBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Webcook\Cms\CoreBundle\Base\BaseRestController;

class BaseRestControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testGetViewWithMessage()
    {
        $method = new \ReflectionMethod('Webcook\Cms\CoreBundle\Base\BaseRestController', 'getViewWithMessage');
        $method->setAccessible(true);

        $data = serialize('Some data');

        $response = $method->invokeArgs(new BaseRestController(), array('data' => $data));

        $this->assertInstanceOf('FOS\RestBundle\View\View', $response);
    }

    public function testGetFormSubmit()
    {
        $this->createTestClient();
        $this->client->request('GET', '/tests');
        $content = $this->client->getResponse()->getContent();
        $this->assertEquals('[]', $content);
    }

    public function testPostFormSubmit()
    {
        $this->createTestClient();

        $date = array('year' => 2015, 'month' => 06, 'day' => 05);

        $crawler = $this->client->request(
            'POST',
            '/tests',
            array(
                'testentity' => array(
                    'variable' => 'new var',
                    'date' => $date,
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $testEntity = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\TestEntity')->find(1);

        $this->assertEquals('new var', $testEntity->getVariable());
        $this->assertEquals(1, $testEntity->getId());
    }

    public function testPutFormSubmit()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/tests/1',
            array(
                'testentity' => array(
                    'variable' => 'put variable',
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $testEntity = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\TestEntity')->find(1);

        $this->assertEquals('put variable', $testEntity->getVariable());
    }
}
