<?php

namespace UCrm\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{

    private $em;


    public function __construct($em) 
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('userId', 'choice', [
                    'choices' => $this->allUsers(),
                    'label' => 'Called on by'
                ])
            ->add('status', 'entity', [
                    'class' => 'UCrmCoreBundle:Status'
                ])
            ->add('lastVisit')
            ->add('territory')
            ->add('streetNumber')
            ->add('streetName')
            ->add('neighborhood')
            ->add('city')
            ->add('zip');
    }

    private function allUsers() 
    {
        $users = $this->em->getRepository('UCrmCoreBundle:User')->findAll();
        $choices = [0 => 'No One'];
        foreach ($users as $user) {
            $choices[$user->getId()] = $user->getFullName();
        }

        return $choices;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCrm\CoreBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ucrm_corebundle_clienttype';
    }
}
