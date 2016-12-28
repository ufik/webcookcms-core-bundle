<?php

namespace Webcook\Cms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class BasicEntityTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testEntityToArray()
    {
        $this->createTestClient();

        $testEntity = new TestEntity();
        $collection = new ArrayCollection();
        $testEntity->setCollection($collection);
        $user = $this->em->getRepository('Webcook\Cms\SecurityBundle\Entity\User')->find(1);
        $testEntity->addToCollection($user);
        $testEntity->setObject($user);
        $testEntity->setVariable('variable');
        $string = $testEntity->__toString();
        $testEntity = $testEntity->toArray();

        $this->assertEquals("Webcook\Cms\CoreBundle\Entity\TestEntity", $string);
        $this->assertEquals('variable', $testEntity['variable']);
        $this->assertEquals(1, $testEntity['object']);
        $this->assertCount(1, $testEntity['collection']);
    }
}
