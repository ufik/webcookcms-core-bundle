<?php

namespace Webcook\Cms\CoreBundle\Base;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webcook\Cms\SecurityBundle\Common\SecurityHelper;

/**
 * TODO documentation.
 */
class BaseRestController extends FOSRestController
{
    private $putParameters;

    /**
     * @param array  $data       [description]
     * @param int    $statusCode [description]
     * @param string $message    [description]
     *
     * @return [type] [description]
     */
    protected function getViewWithMessage($data, $statusCode = 200, $message = '')
    {
        return $this->view(array(
            'message' => $message,
            'data' => $data,
        ), $statusCode);
    }

    /**
     * @param \Symfony\Component\Form\Form $form   [description]
     * @param string $method [description]
     *
     * @return [type] [description]
     */
    protected function formSubmit($form, $method)
    {
        if ($method === 'POST') {
            $parameters = $this->container->get('request_stack')->getCurrentRequest()->request->get($form->getName());
        } elseif ($method === 'PUT') {
            $parameters = $this->getPutParameters($form->getName());
        } elseif ($method === 'GET') {
            $parameters = $this->container->get('request_stack')->getCurrentRequest()->query->all();
        }

        $files = $this->container->get('request_stack')->getCurrentRequest()->files->get($form->getName());
        if (($method === 'POST' || $method === 'PUT') && is_array($files)) {
            $parameters = array_merge($parameters, $files);
        }

        if (!empty($parameters)) {
            $form->submit($parameters);
        }

        return $form;
    }

    /**
     * @param [type] $resourceName [description]
     *
     * @return [type] [description]
     */
    protected function getPutParameters($resourceName)
    {
        if (!empty($this->putParameters)) {
            return $this->putParameters;
        }

        $phpContents = rawurldecode($this->container->get('request_stack')->getCurrentRequest()->getContent());

        parse_str($phpContents, $parameters);
        if (array_key_exists($resourceName, $parameters)) {
            $parameters = $parameters[$resourceName];
        } else {
            $parameters = $this->container->get('request_stack')->getCurrentRequest()->get($resourceName);
        }

        return $this->putParameters = $parameters;
    }

    /**
     *
     * @return [type] [description]
     */
    protected function saveLockVersion($entity)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        $session->set((string) $entity, $entity->getVersion());
    }

    /**
     * @param string $key [description]
     *
     * @return [type] [description]
     */
    protected function getLockVersion($key)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        if ($session->has($key)) {
            return $session->get($key);
        } else {
            return;
        }
    }

    /**
     * Returns entity manager.
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
