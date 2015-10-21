<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FlightWatchType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('flightNumber', null, array(
                'read_only' => true
            ))
            ->add('dep', null, array(
                'read_only' => true
            ))
            ->add('dest', null, array(
                'read_only' => true
            ))
            ->add('desk', 'choice', array(
                'choices' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6'
                )
            ))
            ->add('takeOffTime', null, array(
                'widget' => 'single_text',
                'html5' => 'false',
                'format' => 'dd-MMM-yyyy HH:mm'
                //'date_format' => 'yyyy-MMM-dd',
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_flightwatchofpparse';
    }

}
