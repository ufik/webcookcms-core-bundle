<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\ContentProvider;

use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\Section;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractContentProvider implements ContentProviderInterface
{
    protected $container;

    protected $twig;

    protected $em;

    protected $router;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->twig      = $container->get('twig');
        $this->em        = $container->get('doctrine.orm.entity_manager');
        $this->router    = $container->get('router');
    }
}
