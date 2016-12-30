<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook 
 */

namespace Webcook\Cms\CoreBundle\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings;
use Webcook\Cms\CoreBundle\Form\Type\MenuContentProviderSettingsType;
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
class MenuContentProviderSettingsController extends BaseRestController
{
    /**
     * Get single settings for menu content provider.
     *
     * @param int $id Id of the desired settings.
     *
     * @ApiDoc(
     *  description="Return single settings.",
     *  parameters={
     *      {"name"="PageId", "dataType"="integer", "required"=true, "description"="Page id."},
     *      {"name"="SectionId", "dataType"="integer", "required"=true, "description"="Section id."}
     *  }
     * )
     * @Get("/content-providers/menu/settings/{pageId}/{sectionId}", options={"i18n"=false})
     */
    public function getMenuContentProviderSettingsAction($pageId, $sectionId)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_VIEW);

        $page     = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find($pageId);
        $section  = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Section')->find($sectionId);

        $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->findOneBy(array(
            'page'    => $page,
            'section' => $section
        ));

        if (is_null($settings)) {
            $view = $this->getViewWithMessage(null, 400, 'Settings not found.');
        } else {
            $view = $this->view($settings, 200);
        }

        return $this->handleView($view);
    }

    /**
     * Save new settings.
     *
     * @ApiDoc(
     *  description="Create a new menu content provider settings.",
     *  input="Webcook\Cms\CoreBundle\Form\Type\MenuContentProviderSettingsType",
     *  output="Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings",
     * )
     * @Post("/content-providers/menu/settings", options={"i18n"=false})
     */
    public function postMenuContentProviderSettingsAction()
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_INSERT);

        $response = $this->processSettingsForm(new MenuContentProviderSettings(), 'POST');

        if ($response instanceof MenuContentProviderSettings) {
            $statusCode = 200;
            $message = 'Settings has been added.';
        } else {
            $statusCode = 400;
            $message = 'Error while adding new settings.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Update settings.
     *
     * @param int $id Id of the desired settings.
     *
     * @ApiDoc(
     *  description="Update existing settings.",
     *  input="Webcook\Cms\CoreBundle\Form\Type\MenuContentProviderSettingsType",
     *  output="Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings"
     * )
     * @Put("/content-providers/menu/settings/{id}", options={"i18n"=false})
     */
    public function putMenuContentProviderSettingsAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_EDIT);

        try {
            $settings = $this->getSettingsById($id, $this->getLockVersion((string) new MenuContentProviderSettings()));
        } catch (NotFoundHttpException $e) {
            $settings = new MenuContentProviderSettings();
        }

        $response = $this->processSettingsForm($settings, 'PUT');

        if ($response instanceof MenuContentProviderSettings) {
            $statusCode = 204;
            $message = 'Settings has been updated.';
        } else {
            $statusCode = 400;
            $message = 'Error while updating settings.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Delete settings.
     *
     * @param int $id Id of the desired settings.
     *
     * @ApiDoc(
     *  description="Delete settings.",
     *  parameters={
     *     {"name"="SettingId", "dataType"="integer", "required"=true, "description"="Page id."}
     *  }
     * )
     * @Delete("/content-providers/menu/settings/{id}", options={"i18n"=false})
     */
    public function deleteMenuContentProviderSettingsAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_DELETE);

        $settings = $this->getSettingsById($id);

        $this->getEntityManager()->remove($settings);
        $this->getEntityManager()->flush();

        $view = $this->getViewWithMessage(array(), 200, 'Settings has been deleted.');

        return $this->handleView($view);
    }

    /**
     * Return form if is not valid, otherwise process form and return Page object.
     *
     * @param Page   $settings
     * @param string $method Method of request
     *
     * @return Form [description]
     */
    private function processSettingsForm(MenuContentProviderSettings $settings, String $method = 'POST')
    {
        $form = $this->createForm(MenuContentProviderSettingsType::class, $settings);
        $form = $this->formSubmit($form, $method);
        if ($form->isValid()) {
            $settings = $form->getData();

            if ($settings instanceof MenuContentProviderSettings) {
                $this->getEntityManager()->persist($settings);
            }

            $this->getEntityManager()->flush();

            return $settings;
        }

        return $form;
    }

    /**
     * Get Settings by id.
     *
     * @param int $id Identificator.
     *
     * @return MenuContentProviderSettings
     *
     * @throws NotFoundHttpException If settings object doesn't exist.
     */
    private function getSettingsById(int $id, int $expectedVersion = null)
    {
        if ($expectedVersion) {
            $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->find($id, LockMode::OPTIMISTIC, $expectedVersion);
        } else {
            $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->find($id);
        }

        if (!$settings instanceof MenuContentProviderSettings) {
            throw new NotFoundHttpException('Settings not found.');
        }

        $this->saveLockVersion($settings);

        return $settings;
    }
}
