<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 12/12/15
 * Time: 21:00
 */

namespace AppBundle\Form\Type\FlightWatch;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;



class FlightWatchFinalizePointType extends AbstractType
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
                    'label' => ' Finalize',
                    'button_class' => 'link',
                    'attr' => array(
                        'class' => 'btn-xs',
                        'icon' => 'save'
                    )
                )
            )
            ->setAction(
                $this->router->generate(
                    'fw_finalize_point',
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
                    'id' => 'finalize-point-form-'.$this->flightID,
                    'class' => 'finalize-point-form'
                )
            )
        );

    }

    public function getName()
    {
        return 'flightwatchfinalizepoint';
    }


}