<?php

namespace AppBundle\Form\Type\FlightWatch;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

class FlightWatchDeleteFlightType extends AbstractType
{

    public function __construct($flightID, Router $router)
    {

        $this->flightID = $flightID;
        $this->router = $router;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'button',
                'button',
                array(
                    'label' => ' ',
                    'button_class' => 'default',
                    'attr' => array(
                        'class' => 'btn-lg btn-block',
                        'icon' => 'remove'
                    )
                )
            )
            ->setAction(
                $this->router->generate(
                    'fw_delete_flight',
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
                    'id' => 'delete-flight-form-'.$this->flightID,
                    'class' => 'delete-flight-form'
                )
            )
        );

    }

    public function getName()
    {
        return 'flightwatchdeleteflight';
    }

}
