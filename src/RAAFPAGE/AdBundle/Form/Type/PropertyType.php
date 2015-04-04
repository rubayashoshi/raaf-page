<?php

namespace RAAFPAGE\AdBundle\Form\Type;

use RAAFPAGE\AdBundle\Form\Type\AdPriorityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title','text', array(
            'label' => 'Ad title'
        ));

        $builder->add('description', 'textarea', array(
            'label' => 'Ad description',
            'attr' => array('class' => 'tinymce')
        ));

        $builder->add('rent', 'money', array(
            'divisor' => 100,
        ));
        $builder->add('date_available', 'date', array(
            'input'  => 'datetime',
            'widget' => 'choice',
        ));
        $builder->add('available_to_couple', 'choice', array(
            'choices'  => array('1' => 'Yes', '0' => 'No'),
            'required' => true,
        ));
        $builder->add('is_agent', 'choice', array(
            'choices'  => array('1' => 'Yes', '0' => 'No'),
            'required' => true,
        ));
        $builder->add('contact_phone', 'checkbox', array(
            'label'    => 'Check if you want to be contacted with phone',
            'required' => false,
        ));
        $builder->add('contact_email', 'checkbox', array(
            'label'    => 'Contact ',
            'required' => false,
        ));
        $builder->add('contact_phone_number', 'text', array(
            'label'    => false,
            'required' => false,
        ));
        $builder->add('contact_email_address', 'text', array(
            'label'    => false,
            'required' => false,
        ));

        $builder->add('contact_name');
        $builder->add('rent_period', 'choice', array(
            'choices'  => array('weekly' => 'Weekly', 'monthly' => 'Monthly'),
            'required' => true,
        ));

        $builder->add('link', 'text');

        $builder->add('property_type', 'entity', array(
            'label' => 'Property type',
            'class' => 'RAAFPAGEAdBundle:PropertyType',
            'property' => 'name',
            'expanded' => false,
            'multiple' => false
        ));

//        $builder->add('adTypes', 'entity', array(
//            'label' => 'Ad priority',
//            'class' => 'RAAFPAGEAdBundle:AdType',
//            'property' => 'name',
//            'expanded' => false,
//            'multiple' => true
//        ));
//
//        $builder->add('addTypes', 'checkbox', array(
//            'label'    => 'Check if you want to be contacted with email',
//            'required' => false
//        ));

        //$builder->add('adTypes', 'collection', array('type' => 'raafpage_adbundle_adprioritytype'));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'RAAFPAGE\AdBundle\Entity\Property',
            'csrf_protection'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'property_form';
    }
}
