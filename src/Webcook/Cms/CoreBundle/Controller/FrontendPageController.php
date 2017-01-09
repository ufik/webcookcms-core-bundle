<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontendPageController extends Controller
{
    public function renderAction(Request $request)
    {
        $page = $this->getDoctrine()->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find($request->attributes->get('page'));
        
        $pageSections = $page->getSections(true);
        $sections     = [];
        foreach ($pageSections as $pageSection) {
            $section = $pageSection->getSection();

            $contentProvider                 = $this->get($pageSection->getContentProvider()->getName());
            $order = $pageSection->getOrder();

            if (!array_key_exists($section->getName(), $sections)) {
                $sections[$section->getName()] = array();
            }

            while (array_key_exists($order, $sections[$section->getName()])) {
                $order++;
            }

            $sections[$section->getName()][$order] = $contentProvider->getContent($page, $section);
        }

        foreach ($sections as &$section) {
            ksort($section);
        }

        return $this->render('WebcookCmsCoreBundle::'.$page->getLayout().'.layout.html.twig', array(
            'sections' => $sections,
            'page'     => $page
        ));
    }
}
