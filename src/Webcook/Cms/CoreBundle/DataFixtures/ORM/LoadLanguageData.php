<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook 
 */

namespace Webcook\Cms\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Webcook\Cms\CoreBundle\Entity\Language;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Language fixtures for tests.
 */
class LoadLanguageData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * System container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Entity manager.
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->addLanguage('Čeština', 'cs', true);
        $this->addLanguage('English', 'en');
        $this->addLanguage('Deutsch', 'de');
        
        $this->manager->flush();
    }

    private function addLanguage(String $title, String $abbr, Bool $default = false)
    {
        $language = new Language();
        $language->setTitle($title)
                 ->setAbbr($abbr)
                 ->setDefault($default);

        $this->manager->persist($language);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 0;
    }
}
