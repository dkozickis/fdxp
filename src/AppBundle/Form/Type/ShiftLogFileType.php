<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ShiftLogFileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('file')
            ->add('upload', 'submit')
            ->getForm();
    }

    public function getName()
    {
        return 'shiftlogfile';
    }

}
