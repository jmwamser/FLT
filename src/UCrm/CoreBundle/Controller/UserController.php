<?php
namespace UCrm\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use UCrm\CoreBundle\Entity\User;
use UCrm\CoreBundle\Form\UserLimitedType;

/**
 * User controller.
 *
 * @Route("/me")
 */
class UserController extends Controller implements AuthControllerInterface
{

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/", name="me_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction()
    {
        $auth = $this->get('core.auth.action_listener');
        $entity = $auth::$user;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserLimitedType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/", name="me_update")
     * @Method("PUT")
     * @Template("UCrmCoreBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $auth = $this->get('core.auth.action_listener');
        $entity = $auth::$user;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(new UserLimitedType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('me_edit'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
}
