<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CoreBundle\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;
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
     */
    public function getMenuSettingsAction($pageId = null, $sectionId = null)
    {
        $settings = null;
        if (!is_null($pageId) && !is_null($sectionId)) {
            $page     = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find($pageId);
            $section  = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\Section')->find($sectionId);

            $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\MenuContentProviderSettings')->findOneBy(array(
                'page'    => $page,
                'section' => $section
            ));
        }
        

        if (is_null($settings)) {
            $view = $this->getViewWithMessage(null, 404, 'Settings not found.');
        } else {
            $view = $this->view($settings, 200);
        }

        return $this->handleView($view);
    }
}
