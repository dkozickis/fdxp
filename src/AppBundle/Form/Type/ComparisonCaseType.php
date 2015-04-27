<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComparisonCaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('basic')
            ->add('comparison', 'entity', array(
                //'attr' => array('class' => 'hidden'),
                //'label_attr' => array('class' => 'hidden'),
                'read_only' => true,
                'class' => 'AppBundle:Comparison',
                'query_builder' => function(EntityRepository $entityRepository) use ($options){
                    return $entityRepository->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $options['comparison']);
                }
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ComparisonCase',
            'comparison' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_comparisoncase';
    }
}
