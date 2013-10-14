<?php

namespace UCrm\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TerritoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('user', 'entity', [
                    'class' => 'UCrmCoreBundle:User',
                    'property'  => 'fullName',
                    'label'=> 'Check Out To'
                ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCrm\CoreBundle\Entity\Territory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ucrm_corebundle_territorytype';
    }
}
