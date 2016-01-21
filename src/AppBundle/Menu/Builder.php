<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Common\Persistence\ManagerRegistry;

class Builder extends ContainerAware
{
    private $factory;
    private $managerRegistry;
    private $requestStack;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, ManagerRegistry $managerRegistry, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->managerRegistry = $managerRegistry;
        $this->requestStack = $requestStack;
    }

    public function createMainMenu(SecurityContext $securityContext)
    {
        $menu = $this->factory->createItem('root');

        if ($securityContext->isGranted('ROLE_FD')) {
            $menu->addChild('Shift Log', array('route' => 'shiftlog_index'));
            $menu['Shift Log']->addChild('Current', array('route' => 'shiftlog_index'));
            $menu['Shift Log']->addChild('Archive', array('route' => 'shiftlog_archive_select'));

            $menu->addChild('Flight Watch', array('route' => 'fw_index'));
            $menu['Flight Watch']->addChild('Current', array('route' => 'fw_index'));
            $menu['Flight Watch']->addChild('Archive', array('route' => 'fw_archive_select'));

        }

        if ($securityContext->isGranted('ROLE_TFD')) {
            $menu->addChild('Route comparisons', array('route' => 'compare'));
        }

        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        } else {
            $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        }

        return $menu;
    }

    public function createComparisonMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Comparison list', array('route' => 'compare'));

        switch ($this->requestStack->getCurrentRequest()->get('_route')) {
            case 'compare_case':
                $menu = $this->compareMenuBuildUp('comp', $menu);
                break;
            case 'comparison_case_calc':
                $menu = $this->compareMenuBuildUp('case', $menu);
                break;
            case 'comparison_case_calc_edit':
                $menu = $this->compareMenuBuildUp('calc', $menu);
                break;

        }

        return $menu;
    }


    /**
     * @param string $build
     */
    private function compareMenuBuildUp($build = null, ItemInterface $menu)
    {
        $em = $this->managerRegistry->getManager();
        $rs = $this->requestStack;

        switch ($build) {
            case 'comp':
                $comp = $em
                    ->getRepository('AppBundle:Comparison')
                    ->find($rs->getCurrentRequest()->get('comp_id'));
                $menu->addChild($comp->getName());
                $menu->setCurrent(true);
                break;
            case 'comp_from_case_id':
                $comp = $em->getRepository('AppBundle:ComparisonCase')
                    ->find($rs->getCurrentRequest()->get('case_id'))->getComparison();
                $menu->addChild($comp->getName(), array(
                    'route' => 'compare_case',
                    'routeParameters' => array(
                        'comp_id' => $comp->getId(),),));
                break;
            case 'case':
                $menu = $this->compareMenuBuildUp('comp_from_case_id', $menu, $em, $rs);
                $case = $em
                    ->getRepository('AppBundle:ComparisonCase')
                    ->find($rs->getCurrentRequest()->get('case_id'));
                if ($rs->getCurrentRequest()->get('_route') == 'comparison_case_calc_edit') {
                    $menu->addChild($case->getName(), array(
                        'route' => 'comparison_case_calc',
                        'routeParameters' => array(
                            'case_id' => $rs->getCurrentRequest()->get('case_id'),
                        ),
                    ));
                } else {
                    $menu->addChild($case->getName());
                    $menu->setCurrent(true);
                }
                break;
            case 'calc':
                $menu = $this->compareMenuBuildUp('case', $menu, $em, $rs);
                $calc = $em->getRepository('AppBundle:ComparisonCaseCalc')
                    ->find($rs->getCurrentRequest()->get('id'));
                $menu->addChild($calc->getCitypair());
                $menu->setCurrent(true);
                break;
        }

        return $menu;
    }
}
