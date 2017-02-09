<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\PageSection;
use Webcook\Cms\CoreBundle\Form\Type\PageType;
use Webcook\Cms\CoreBundle\Form\Type\PageSectionType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Webcook\Cms\SecurityBundle\Authorization\Voter\WebcookCmsVoter;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Doctrine\DBAL\LockMode;

/**
 * Page controller.
 */
class PageController extends BaseRestController
{
    /**
     * Update page section order.
     *
     * @param int $id Id of the desired Page.
     *
     */
    public function orderAction($id)
    {
        $order = $this->getPutParameters('order');
        $pageSection = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\PageSection')->find($id);

        if (!is_null($pageSection)) {
            if (!is_numeric($order)) {
                throw new BadRequestHttpException('Order is not numeric.');
            }

            $pageSection->setOrder($order);
            $this->getEntityManager()->flush();
        } else {
            throw new NotFoundHttpException('Page section does not exist.');
        }

        return $pageSection;
    }
}
