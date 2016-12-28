<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook 
 */

namespace Webcook\Cms\CoreBundle\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Form\Type\PageType;
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
     * Get all Pages.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Return collection of Pages.",
     * )
     * @Get(options={"i18n"=false})
     */
    public function getPagesAction()
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_VIEW);

        $pages = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();
        $view = $this->view($pages, 200);

        return $this->handleView($view);
    }

    /**
     * Get single Page.
     *
     * @param int $id Id of the desired Page.
     *
     * @ApiDoc(
     *  description="Return single Page.",
     *  parameters={
     *      {"name"="PageId", "dataType"="integer", "required"=true, "description"="Page id."}
     *  }
     * )
     * @Get(options={"i18n"=false})
     */
    public function getPageAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_VIEW);

        $page = $this->getPageById($id);
        $view = $this->view($page, 200);

        return $this->handleView($view);
    }

    /**
     * Save new Page.
     *
     * @ApiDoc(
     *  description="Create a new Page.",
     *  input="Webcook\Cms\CoreBundle\Form\Type\PageType",
     *  output="Webcook\Cms\CoreBundle\Entity\Page",
     * )
     * @Post(options={"i18n"=false})
     */
    public function postPagesAction()
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_INSERT);

        $response = $this->processPageForm(new Page(), 'POST');

        if ($response instanceof Page) {
            $statusCode = 200;
            $message = 'Page has been added.';
        } else {
            $statusCode = 400;
            $message = 'Error while adding new Page.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Update Page.
     *
     * @param int $id Id of the desired Page.
     *
     * @ApiDoc(
     *  description="Update existing Page.",
     *  input="Webcook\Cms\CoreBundle\Form\Type\PageType",
     *  output="Webcook\Cms\CoreBundle\Entity\Page"
     * )
     * @Put(options={"i18n"=false})
     */
    public function putPageAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_EDIT);

        try {
            $page = $this->getPageById($id, $this->getLockVersion((string) new Page()));
        } catch (NotFoundHttpException $e) {
            $page = new Page();
        }

        $response = $this->processPageForm($page, 'PUT');

        if ($response instanceof Page) {
            $statusCode = 204;
            $message = 'Page has been updated.';
        } else {
            $statusCode = 400;
            $message = 'Error while updating Page.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Delete Page.
     *
     * @param int $id Id of the desired Page.
     *
     * @ApiDoc(
     *  description="Delete Page.",
     *  parameters={
     *     {"name"="PageId", "dataType"="integer", "required"=true, "description"="Page id."}
     *  }
     * )
     * @Delete(options={"i18n"=false})
     */
    public function deletePageAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_DELETE);

        $page = $this->getPageById($id);

        $this->getEntityManager()->remove($page);
        $this->getEntityManager()->flush();

        $view = $this->getViewWithMessage(array(), 200, 'Page has been deleted.');

        return $this->handleView($view);
    }

    /**
     * Return form if is not valid, otherwise process form and return Page object.
     *
     * @param Page   $page
     * @param string $method Method of request
     *
     * @return Form [description]
     */
    private function processPageForm(Page $page, String $method = 'POST')
    {
        $form = $this->createForm(PageType::class, $page);
        $form = $this->formSubmit($form, $method);
        if ($form->isValid()) {
            $page = $form->getData();

            if ($page instanceof Page) {
                $sections = $page->getSections();

                foreach ($sections as $section) {
                    $section->setPage($page);
                }

                $this->getEntityManager()->persist($page);
            }

            $this->getEntityManager()->flush();

            return $page;
        }

        return $form;
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
