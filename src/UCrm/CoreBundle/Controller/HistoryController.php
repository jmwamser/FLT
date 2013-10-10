<?php

namespace UCrm\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UCrm\CoreBundle\Entity\TerritoryHistory;
use UCrm\CoreBundle\Form\TerritoryHistoryType;

/**
 * History controller.
 * @Route("/territory/{territory_id}")
 */
class HistoryController extends Controller
{

    /**
     * Lists all TerritoryHistory entities.
     *
     * @Route("/history", name="history")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($territory_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UCrmCoreBundle:TerritoryHistory')
            ->findAll(['territory_id' => $territory_id]);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TerritoryHistory entity.
     *
     * @Route("/history", name="history_create")
     * @Method("POST")
     * @Template("UCrmCoreBundle:TerritoryHistory:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity  = new TerritoryHistory();
        $form = $this->createForm(new TerritoryHistoryType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('history_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new TerritoryHistory entity.
     *
     * @Route("/history/new", name="history_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TerritoryHistory();
        $form   = $this->createForm(new TerritoryHistoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TerritoryHistory entity.
     *
     * @Route("/history/{id}", name="history_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:TerritoryHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TerritoryHistory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TerritoryHistory entity.
     *
     * @Route("/history/{id}/edit", name="history_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:TerritoryHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TerritoryHistory entity.');
        }

        $editForm = $this->createForm(new TerritoryHistoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing TerritoryHistory entity.
     *
     * @Route("/history/{id}", name="history_update")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:TerritoryHistory:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:TerritoryHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TerritoryHistory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TerritoryHistoryType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('history_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TerritoryHistory entity.
     *
     * @Route("/history/{id}", name="history_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UCrmCoreBundle:TerritoryHistory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TerritoryHistory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('history'));
    }

    /**
     * Creates a form to delete a TerritoryHistory entity by id.
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
