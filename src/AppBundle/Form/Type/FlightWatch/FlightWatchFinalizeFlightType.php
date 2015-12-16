<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 12/12/15
 * Time: 20:55
 */

namespace AppBundle\Form\Type\FlightWatch;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;


use Symfony\Component\Form\AbstractType;

class FlightWatchFinalizeFlightType extends AbstractType
{

    public function __construct($flightID, Router $router)
    {

        $this->flightID = $flightID;
        $this->router = $router;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('button', 'button',
                array(
                    'label' => ' ',
                    'button_class' => 'default',
                    'attr' => array(
                        'class' => 'btn-lg btn-block',
                        'icon' => 'save'
                    )
                ))
            ->setAction(
                $this->router->generate(
                    'fw_finalize_flight',
                    array(
                        'id' => $this->flightID
                    )
                )
            );

    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults(
            array(
                'attr' => array(
                    'id' => 'finalize-flight-form-'.$this->flightID,
                    'class' => 'finalize-flight-form'
                )
            )
        );

    }

    public function getName()
    {
        return 'flightwatchfinalizeflight';
    }

}