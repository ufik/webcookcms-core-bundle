<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\ContentProvider;

use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\Section;

class MenuContentProvider extends AbstractContentProvider
{
    public function getContent(Page $page, Section $section): string
    {
        $pageRepo = $this->em->getRepository('\Webcook\Cms\CoreBundle\Entity\Page');
        $router   = $this->router;

        return $this->twig->render(
            'WebcookCmsCoreBundle:ContentProvider:menu.html.twig',
            array(
                'htmlTree' => $pageRepo->childrenHierarchy(
                    $pageRepo->findAll()[0], /* starting from root nodes */
                    false, /* true: load all children, false: only direct */
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
}
