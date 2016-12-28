<?php

namespace Webcook\Cms\CoreBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Webcook\Cms\SecurityBundle\Entity\User;
use Symfony\Component\Filesystem\Filesystem;

abstract class BasicTestCase extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $client;

    protected $container;

    protected function loadFixtures(array $classNames, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $classNames[] = 'Webcook\Cms\SecurityBundle\DataFixtures\ORM\LoadResourcesData';
        $classNames[] = 'Webcook\Cms\SecurityBundle\DataFixtures\ORM\LoadRoleData';
        $classNames[] = 'Webcook\Cms\SecurityBundle\DataFixtures\ORM\LoadUserData';

        parent::loadFixtures($classNames, $omName, $registryName, $purgeMode);
    }

    public function clearCache()
    {
        echo 'clear cache';
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));
    }

    /**
     * {@inheritDoc}
     */
    public function setUp($clearCache = false)
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        if ($clearCache) {
            $fs = new Filesystem();
            echo 'clear cache set up';
            $fs->remove($this->container->getParameter('kernel.cache_dir'));
        }

        $this->loadFixtures(array());
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    protected function createTestClient($login = true)
    {
        $this->client = static::createClient();

        if ($login) {
            $this->logIn();
        }
    }

    protected function printResponseContent()
    {
        print_r($this->client->getResponse()->getContent());
    }

    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $user = $this->em->getRepository('Webcook\Cms\SecurityBundle\Entity\User')->find(1);
        $this->em->detach($user);

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken($user, 'test', $firewall);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
