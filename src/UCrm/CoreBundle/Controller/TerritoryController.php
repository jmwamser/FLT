<?php

namespace UCrm\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UCrm\CoreBundle\Entity\Territory;
use UCrm\CoreBundle\Entity\Setting;
use UCrm\CoreBundle\Form\TerritoryType;
use UCrm\CoreBundle\Form\TerritoryCoordsType;
use UCrm\CoreBundle\Form\TerritoryCheckoutType;
use UCrm\CoreBundle\Form\NewTerritoryType;

/**
 * Territory controller.
 *
 * @Route("/territories")
 */
class TerritoryController extends Controller implements AuthControllerInterface
{

    /**
     * Lists all Territory entities.
     *
     * @Route("/", name="territories")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UCrmCoreBundle:Territory')->findAllWithUser();
        $checkoutForm = $this->createForm(new TerritoryCheckoutType(), new Territory());
        //var_dump($entities[0]->getUser());
        //exit;

        return array(
            'entities' => $entities,
            'checkout_form' => $checkoutForm->createView()
        );
    }
    /**
     * Map action
     *
     * @Route("/map", name="territories_map")
     * @Method("GET")
     * @Template("UCrmCoreBundle:Territory:map.html.twig")
     */
    public function mapAction() 
    {
        $em = $this->getDoctrine()->getManager();

        $entities   = $em->getRepository('UCrmCoreBundle:Territory')->findAll();
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        $coords = [];
        foreach ($entities as $entity) {
            if (!$entity->hasCoords()) {
                continue;
            }
            $coords[$entity->getId()] = json_decode($entity->getCoords());
        }

        return [
            'entities' => $entities,
            'googleApi' => $googleSetting,
            'coords'    => json_encode($coords)
        ];
    }
    /**
     * Creates a new Territory entity.
     *
     * @Route("/", name="territories_create")
     * @Method("POST")
     * @Template("UCrmCoreBundle:Territory:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Territory();
        $form = $this->createForm(new TerritoryType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('territories_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Territory entity.
     *
     * @Route("/new", name="territories_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Territory();
        $form   = $this->createForm(new NewTerritoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Territory entity.
     *
     * @Route("/{id}", name="territories_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Territory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Territory entity.
     *
     * @Route("/{id}/edit", name="territories_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Territory entity.');
        }

        $editForm = $this->createForm(new TerritoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'googleApi' => $googleSetting
        );
    }

    /**
     * Displays a form to edit an existing Territory entity.
     *
     * @Route("/{id}/coords", name="territories_coords")
     * @Method("GET")
     * @Template()
     */
    public function coordsAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Territory entity.');
        }

        $editForm = $this->createForm(new TerritoryCoordsType(), $entity);
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);


        $db = $em->getRepository('UCrmCoreBundle:Territory')->createQueryBuilder('t');
        $db->where('t.id != :id')
            ->setParameter('id', $id);
        $terrs = $db->getQuery()->getResult();
        $coords = [];
        foreach ($terrs as $terr) {
            if (!$terr->hasCoords()) {
                continue;
            }
            $coords[$terr->getId()] = json_decode($terr->getCoords());
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'googleApi' => $googleSetting,
            'coords'    => json_encode($coords)
        );
    } 
    /**
     * Displays a form to edit an existing Territory entity.
     *
     * @Route("/{id}/coords")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:Territory:coords.html.twig")
     */
    public function coordsUpdateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Territory entity.');
        }
        
        $googleSetting = $em->getRepository('UCrmCoreBundle:Setting')->findOneBy(['name' => 'google/api/key']);
        $editForm = $this->createForm(new TerritoryCoordsType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('territories_edit', array('id' => $id)));
        }

        return array(
            'edit_form' => $editForm,
            'entity'    => $entity,
            'googleApi' => $googleSetting
        );
    } 

    /**
     * Edits an existing Territory entity.
     *
     * @Route("/{id}", name="territories_update")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:Territory:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Territory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TerritoryType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('territories_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Territory entity.
     *
     * @Route("/{id}/checkout", name="territory_checkout")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:Territory:edit.html.twig")
     */
    public function checkoutAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Territory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TerritoryType(), $entity);
        $checkoutForm = $this->createForm(new TerritoryCheckoutType(), $entity);
        $checkoutForm->submit($request);

        if ($checkoutForm->isValid()) {
            $entity->setStatus(Territory::StatusCheckedOutNotRecorded);
            $entity->setCheckedOutOn(new \DateTime());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('territories_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'checkout_form' => $checkoutForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Territory entity.
     *
     * @Route("/{id}", name="territories_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UCrmCoreBundle:Territory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Territory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('territories'));
    }

    /**
     * Creates a form to delete a Territory entity by id.
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
