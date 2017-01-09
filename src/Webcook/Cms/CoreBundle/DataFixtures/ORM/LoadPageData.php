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
use Webcook\Cms\I18nBundle\Entity\Language;
use Webcook\Cms\CoreBundle\Entity\Section;
use Webcook\Cms\CoreBundle\Entity\ContentProvider;
use Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings;
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

        // TODO: refactor
        $languages = $this->manager->getRepository('Webcook\Cms\I18nBundle\Entity\Language')->findAll();
        $sections = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\Section')->findAll();
        $contentProviders = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\ContentProvider')->findAll();

        // english main menu
        $mainen = $this->addPage('Main', $languages[1]);
        $home = $this->addPage('Home', $languages[1], $mainen);
        $this->addPage('Photogallery', $languages[1], $home);
        $this->addPage('Contact', $languages[1], $mainen);

        $settings = new MenuContentProviderSettings();
        $settings->setPage($mainen)
            ->setSection($sections[0])
            ->setParent($mainen);
        
        $this->manager->persist($settings);

        // footer menu
        $this->addPage('Footer', $languages[1]);

        // Czech main menu
        $main = $this->addPage('Main', $languages[0]);
        $this->addPage('Uvod', $languages[0], $main);

        $settings = new MenuContentProviderSettings();
        $settings->setPage($main)
            ->setSection($sections[0])
            ->setParent($main);

        $this->manager->persist($settings);

        $settings = new MenuContentProviderSettings();
        $settings->setPage($main)
            ->setSection($sections[1])
            ->setParent($mainen);

        $this->manager->persist($settings);

        $this->manager->flush();
    }

    private function addPage(String $title, Language $language, $parent = null)
    {
        $page = new Page();
        
        $page->setTitle($title);
        $page->setLanguage($language);
        $page->setLayout('default');
        $page->setParent($parent);
        
        $this->manager->persist($page);

        return $page;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
