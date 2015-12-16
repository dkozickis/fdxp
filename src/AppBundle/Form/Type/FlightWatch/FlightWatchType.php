<?php

namespace AppBundle\Form\Type\FlightWatch;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class FlightWatchType extends AbstractType
{
    protected $request;

    public function __construct(Request $request){

        $this->request = $request;

    }

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
            ->add('ref', 'hidden', array(
                'mapped' => false,
                'data' => $this->request->headers->get('referer')
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
