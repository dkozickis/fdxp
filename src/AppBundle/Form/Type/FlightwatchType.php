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
            ->add('takeOffTime', null, array(
                'date_format' => 'yyyy-MMM-dd'
                //'data' => new \DateTime('today')
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