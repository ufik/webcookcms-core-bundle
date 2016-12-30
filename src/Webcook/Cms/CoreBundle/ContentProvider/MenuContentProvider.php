<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\ContentProvider;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\Section;
use Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings;

class MenuContentProvider extends AbstractContentProvider
{
    private $pageRepository;

    /** @inheritDoc */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->pageRepository = $this->em->getRepository('\Webcook\Cms\CoreBundle\Entity\Page');
    }

    /** @inheritDoc */
    public function getContent(Page $page, Section $section): string
    {
        $router   = $this->router;
        $settings = $this->resolveSettings($page, $section);

        return $this->twig->render(
            'WebcookCmsCoreBundle:ContentProvider:menu.html.twig',
            array(
                'htmlTree' => $this->pageRepository->childrenHierarchy(
                    $settings->getParent(), /* null: starting from root nodes */
                    $settings->getDirectChildren(), /* true: load all children, false: only direct */
                    array(
                        'decorate' => true,
                        'html' => true,
                        'nodeDecorator' => function($node) use ($router) {
                            $routeName = $node['languageAbbr'].'_'.str_replace('/', '_', $node['slug']);
                            
                            $url = $router->generate($routeName);
                            return '<a href="'.$url.'">'.$node['title'].'</a>';
                        }
                    )
                )
            )
        );
    }

    private function resolveSettings(Page $page, Section $section): MenuContentProviderSettings
    {
        // TODO add caching, invalidate after change of settings table ?
        $settings = null;
        while(is_null($settings) && $page) {
            $settings = $this->em->getRepository('\Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->findOneBy(array(
                'page'    => $page,
                'section' => $section
            ));

            $page = $page->getParent();
        }

        // fallback, pick first page TODO: rewrite to default page
        if (is_null($settings)) {
            $settings = new MenuContentProviderSettings;
            $settings->setParent($this->pageRepository->findAll()[0]);
        }

        return $settings;
    }
}
