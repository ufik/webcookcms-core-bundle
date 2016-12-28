<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook Communications
 */

namespace Webcook\Cms\CoreBundle\Tests\Helpers\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;
use Doctrine\DBAL\LockMode;
use Doctrine\Common\Collections\ArrayCollection;
use Webcook\Cms\CoreBundle\Tests\Helpers\Form\Type\TestType;
use Webcook\Cms\CoreBundle\Entity\TestEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Test RESTful controller.
 */
class TestController extends BaseRestController
{
    public function getTestsAction()
    {
        $result = new ArrayCollection();

        $view = $this->view($result, 200);

        return $this->handleView($view);
    }

    public function getTestAction($id)
    {
        $testEntity = $this->getTestEntityById($id);
        $view = $this->view($testEntity, 200);

        return $this->handleView($view);
    }

    public function postTestsAction()
    {
        $response = $this->processTestForm(new TestEntity(), 'POST');

        if ($response instanceof TestEntity) {
            $statusCode = 200;
            $message = 'Test has been added.';
        } else {
            $statusCode = 400;
            $message = 'Error while adding new test.';
        }
        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    public function putTestsAction($id)
    {
        try {
            $testEntity = $this->getTestEntityById($id, $this->getLockVersion((string) new TestEntity()));
        } catch (NotFoundHttpException $e) {
            $testEntity = new TestEntity();
        }

        $response = $this->processTestForm($testEntity, 'PUT');

        if ($response instanceof TestEntity) {
            $statusCode = 204;
            $message = 'Test entity has been updated.';
        } else {
            $statusCode = 400;
            $message = 'Error while updating test entity.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * @param TestEntity $test
     */
    private function processTestForm($test = null, $method = 'POST')
    {
        $form = $this->createForm(TestType::class, $test);
        $form = $this->formSubmit($form, $method);

        if ($form->isValid()) {
            $testEntity = $form->getData();

            $this->getDoctrine()->getManager()->persist($testEntity);
            $this->getDoctrine()->getManager()->flush();

            return $testEntity;
        }

        return $form;
    }

    /**
     * @param int  $id              [description]
     * @param int  $expectedVersion [description]
     *
     * @return TestEntity [description]
     */
    public function getTestEntityById($id, $expectedVersion = null)
    {
        if ($expectedVersion) {
            $testEntity = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\TestEntity')->find($id, LockMode::OPTIMISTIC, $expectedVersion);
        } else {
            $testEntity = $this->getEntityManager()->getRepository('Webcook\Cms\CoreBundle\Entity\TestEntity')->find($id);
        }

        if (!$testEntity instanceof TestEntity) {
            throw new NotFoundHttpException('TestEntity not found.');
        }

        $this->saveLockVersion($testEntity);

        return $testEntity;
    }
}
