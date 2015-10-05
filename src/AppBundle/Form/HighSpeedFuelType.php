<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HighSpeedFuelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                //'format' => 'text',
                'widget' => 'single_text',
                'html5' => 'false',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ))
            ->add('flightNumber')
            ->add('depIATA', null, array(
                //'read_only' => true,
                'label' => 'DEP',
                'attr' => array('style' => 'width: 50px;')
            ))
            ->add('destIATA', null, array(
                //'read_only' => true,
                'label' => 'DEST',
                'attr' => array('style' => 'width: 50px;')
            ))
            ->add('normalFlightTime')
            ->add('highSpeedFlightTime')
            ->add('normalCost')
            ->add('highSpeedCost')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\HighSpeedFuel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_highspeedfuel';
    }
}
