<?php

namespace UCrm\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TerritoryHistoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('territoryId', 'entity', [
                    'class' => 'UCrmCoreBundle:Territory',
                    'property' => 'id',
                ])
            ->add('checkedOutBy', 'entity', [
                    'class' => 'UCrmCoreBundle:User',
                    'property' => 'fullName',
                ])
            ->add('checkedOutOn')
            ->add('checkedInOn');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UCrm\CoreBundle\Entity\TerritoryHistory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ucrm_corebundle_territoryhistorytype';
    }
}
