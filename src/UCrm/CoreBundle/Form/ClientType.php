<?php

namespace UCrm\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

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
            ->add('territory', 'entity', [
                    'class' => 'UCrmCoreBundle:Territory',
                    'property' => 'code',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->orderBy('t.code', 'ASC');
                    },
                ])
            ->add('streetNumber')
            ->add('streetName')
            ->add('neighborhood')
            ->add('city')
            ->add('zip')
            ->add('source', 'entity', [
                    'class' => 'UCrmCoreBundle:Source',
                    'property' => 'name'
                ])
            ->add('description')
            ->add('lat')
            ->add('lon');
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
