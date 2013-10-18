<?php

namespace UCrm\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UCrm\CoreBundle\Entity\Client;
use UCrm\CoreBundle\Form\ClientType;
use UCrm\CoreBundle\EventListener\AuthListener;

/**
 * Client controller.
 *
 * @Route("/people")
 */
class ClientController extends Controller implements AuthControllerInterface
{

    /**
     * Lists all Client entities.
     *
     * @Route("/", name="people")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        // Verify that user is a super admin
        $auth = $this->get('core.auth.action_listener');
        $user = $auth::$user;
        if (($user->getPermissions() & 128) != 128) {
            throw new AccessDeniedHttpException(AuthListener::PermsMessage);
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UCrmCoreBundle:Client')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Lists all Client entities.
     *
     * @Route("/mine", name="people_mine")
     * @Method("GET")
     * @Template("UCrmCoreBundle:Client:index.html.twig")
     */
    public function myAction()
    {
        $auth = $this->get('core.auth.action_listener');
        $user = $auth::$user;

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('UCrmCoreBundle:Client')->findAllOfUser($user);

        return [
            'entities' => $entities,
            'user' => $user
        ];
    }
    /**
     * Lists all Client entities.
     *
     * @Route("/map", name="peoples_map")
     * @Method("GET")
     * @Template()
     */
    public function allMapAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UCrmCoreBundle:Client')->findAll();
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        $markers = [];
        foreach ($entities as $entity) {
            $markers[] = [
                'lb'    => $entity->getLat(),
                'mb'    => $entity->getLon(),
                'title' => $entity->getFullName()
            ];
        }

        return array(
            'entities' => $entities,
            'googleApi'=> $googleSetting,
            'markers'   => json_encode($markers)
        );
    }
    /**
     * Creates a new Client entity.
     *
     * @Route("/", name="people_create")
     * @Method("POST")
     * @Template("UCrmCoreBundle:Client:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Client();
        $form = $this->createForm(new ClientType($this->getDoctrine()->getManager()), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('people_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Client entity.
     *
     * @Route("/new", name="people_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Client();
        $form   = $this->createForm(new ClientType($this->getDoctrine()->getManager()), $entity);
        $em = $this->getDoctrine()->getManager();
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'googleApi' => $googleSetting
        );
    }

    /**
     * Finds and displays a Client entity.
     *
     * @Route("/{id}", name="people_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a map for Client entity.
     *
     * @Route("/{id}/map", name="people_map")
     * @Method("GET")
     * @Template()
     */
    public function mapAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        return array(
            'entity'      => $entity,
            'googleApi' => $googleSetting
        );
    }

    /**
     * Displays a form to edit an existing Client entity.
     *
     * @Route("/{id}/edit", name="people_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Client')->find($id);
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $editForm = $this->createForm(new ClientType($em), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'googleApi' => $googleSetting
        );
    }

    /**
     * Edits an existing Client entity.
     *
     * @Route("/{id}", name="people_update")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:Client:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ClientType($em), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('people_edit', array('id' => $id)));
        }

        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'googleApi' => $googleSetting
        );
    }
    /**
     * Deletes a Client entity.
     *
     * @Route("/{id}", name="people_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UCrmCoreBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('people'));
    }

    /**
     * Creates a form to delete a Client entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
