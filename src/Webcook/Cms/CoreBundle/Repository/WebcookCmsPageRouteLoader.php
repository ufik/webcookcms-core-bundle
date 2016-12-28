<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Repository;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class WebcookCmsPageRouteLoader extends Loader
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where($criteria->expr()->gt('lvl', 0));

        $pages = $this->em->getRepository('\Webcook\Cms\CoreBundle\Entity\Page')->matching($criteria);
        foreach ($pages as $page) {
            $route = new Route($page->getPath(), array(
                '_controller' => 'WebcookCmsCoreBundle:FrontendPage:render',
                'page'        => $page->getId()
            ), array());

            $routes->add($page->getRouteName(), $route);
        }

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'webcookcms_page' === $type;
    }
}