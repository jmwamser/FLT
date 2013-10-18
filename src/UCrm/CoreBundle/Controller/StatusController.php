<?php

namespace UCrm\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UCrm\CoreBundle\Entity\Status;
use UCrm\CoreBundle\Form\StatusType;

/**
 * Status controller.
 *
 * @Route("/statuses")
 */
class StatusController extends Controller implements AuthControllerInterface
{
    
    public $minPerms = 128;

    /**
     * Lists all Status entities.
     *
     * @Route("/", name="statuses")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UCrmCoreBundle:Status')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Status entity.
     *
     * @Route("/", name="statuses_create")
     * @Method("POST")
     * @Template("UCrmCoreBundle:Status:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Status();
        $form = $this->createForm(new StatusType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('statuses'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Status entity.
     *
     * @Route("/new", name="statuses_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Status();
        $form   = $this->createForm(new StatusType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Status entity.
     *
     * @Route("/{id}", name="statuses_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Status entity.
     *
     * @Route("/{id}/edit", name="statuses_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $editForm = $this->createForm(new StatusType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Status entity.
     *
     * @Route("/{id}", name="statuses_update")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:Status:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Status')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Status entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StatusType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('statuses_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Status entity.
     *
     * @Route("/{id}", name="statuses_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UCrmCoreBundle:Status')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Status entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('statuses'));
    }

    /**
     * Creates a form to delete a Status entity by id.
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
