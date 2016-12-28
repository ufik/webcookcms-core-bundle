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
use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\PageSection;
use Webcook\Cms\CoreBundle\Entity\Language;
use Webcook\Cms\CoreBundle\Entity\Section;
use Webcook\Cms\CoreBundle\Entity\ContentProvider;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Page fixtures for tests.
 */
class LoadPageData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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

        $languages = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\Language')->findAll();
        $sections = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\Section')->findAll();
        $contentProviders = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\ContentProvider')->findAll();

        $this->addPage('Main', $languages[0], $sections[0], $contentProviders[0]);
        $this->manager->flush();
        $parent = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();
        $this->addPage('Home', $languages[0], null, null, $parent[0]);

        $this->addPage('Footer', $languages[0], $sections[0], $contentProviders[0], null);

        $this->manager->flush();
    }

    private function addPage(String $title, Language $language, Section $section = null, ContentProvider $contentProvider = null, $parent = null)
    {
        $page = new Page();
        if ($section) {
            $pageSection = new PageSection();
            $pageSection->setPage($page);
            $pageSection->setSection($section);
            $pageSection->setContentProvider($contentProvider);

            $this->manager->persist($pageSection);
        }
        
        $page->setTitle($title);
        $page->setLanguage($language);
        $page->setLayout('default');   
        $page->setParent($parent);
        
        $this->manager->persist($page);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
