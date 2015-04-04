<?php

namespace RAAFPAGE\AdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use JMS\DiExtraBundle\Annotation as DI;

class AdPriorityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('name', 'checkbox', array(
                'required' => false,
            ));
    }

    public function getName()
    {
        return 'adprioritytype';
    }

    public function getDefaultOptions(){
        return array('data_class' => 'RAAFPAGE\AdBundle\Entity\AdType');
    }
}