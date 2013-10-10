<?php

namespace UCrm\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use UCrm\CoreBundle\Entity\User;
use UCrm\CoreBundle\Form\SigninType;
use \DateTime;

class SessionsController extends Controller
{
    /**
     * @Route("/", name="root")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction()
    {
    	$entity = new User();
        $form   = $this->createForm(new SigninType(), $entity);

        return [
        	'entity' => $entity,
        	'form' => $form->createView()
        ];
    }

    /**
     * @Route("/signin", name="signin")
     * @Method({"POST"})
  	 */
    public function signinAction(Request $request)
    {
        $form = $this->createForm(new SigninType(), new User());
        $form->submit($request);
        $data = $form->getData();

    	$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UCrmCoreBundle:User')
                ->findOneBy(['email' => $data->getEmail()]);

        $session = $this->getRequest()->getSession();

        if (!$user->isPasswordMatch($data->getPassword())) {
            $session->getFlashBag()->add('error', 'Unable to log you in at this this');
            return [];
        } 

        $user->setLastLoginAt(new DateTime());
        $em->flush();

        $session->set('user_id', $user->getId()); 
        $session->set('user_hash', $user->getHash());   

        return $this->redirect($this->generateUrl('users'));
    }

    /**
     * @Route("/signout", name="signout")
     * @Method({"GET"})
     */
    public function signoutAction(Request $request)
    {
        $this->getRequest()->getSession()->clear();

        return $this->redirect($this->generateUrl('root'));
    }
}
