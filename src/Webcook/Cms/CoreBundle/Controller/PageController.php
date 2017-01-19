<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * Update Page.
     *
     * @param int $id Id of the desired Page.
     *
     * @ApiDoc(
     *  description="Update order of page section.",
     *  input="Webcook\Cms\CoreBundle\Form\Type\PageSectionType"
     * )
     * @Put("/page-section/order/{id}", options={"i18n"=false})
     */
    public function putPageSectionOrderAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_EDIT);

        $pageSection = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\PageSection')->find($id);

        if (!is_null($pageSection)) {
            $form = $this->createForm(PageSectionType::class);
            $form = $this->formSubmit($form, 'PUT');
            if ($form->isValid()) {
                $data = $form->getData();
                $pageSection->setOrder($data['order']);
                $this->getEntityManager()->flush();

                $statusCode = 204;
                $message = 'Page section has been updated.';
            } else {
                $statusCode = 400;
                $message = 'Given data are not valid.';
            }
        } else {
            $statusCode = 404;
            $message = 'Page section not found.';
        }

        $view = $this->getViewWithMessage(null, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Get Page by id.
     *
     * @param int $id [description]
     *
     * @return Page
     *
     * @throws NotFoundHttpException If Page doesn't exist
     */
    private function getPageById($id, $expectedVersion = null)
    {
        if ($expectedVersion) {
            $page = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find($id, LockMode::OPTIMISTIC, $expectedVersion);
        } else {
            $page = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find($id);
        }

        if (!$page instanceof Page) {
            throw new NotFoundHttpException('Page not found.');
        }

        $this->saveLockVersion($page);

        return $page;
    }
}
