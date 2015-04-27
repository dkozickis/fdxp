<?php

namespace AppBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContext;

class Builder extends ContainerAware
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(SecurityContext $securityContext)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Shift Log', array('route' => 'shiftlog_index'));

        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        } else {
            $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        }

        return $menu;
    }

    public function createComparisonMenu(RequestStack $requestStack, EntityManager $entityManager)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Comparison', array('route' => 'compare'));

        switch ($requestStack->getCurrentRequest()->get('_route')) {
            case 'compare_case':
                $comp = $entityManager
                    ->getRepository('AppBundle:Comparison')
                    ->find($requestStack->getCurrentRequest()->get('comp_id'));
                $menu->addChild($comp->getName());
                $menu->setCurrent(1);
                break;
            case 'comparison_case_calc':
                $case = $entityManager
                    ->getRepository('AppBundle:ComparisonCase')
                    ->find($requestStack->getCurrentRequest('')->get('case_id'));
                $case_name = $case->getName();

                $comp_name = $case->getComparison()->getName();
                $comp_id = $case->getComparison()->getId();

                $menu->addChild($comp_name, array('route' => 'compare_case',
                    'routeParameters' => array('comp_id' => $comp_id), ));

                $menu->addChild($case_name);
                $menu->setCurrent(1);
                break;
        }

        return $menu;
    }
}
