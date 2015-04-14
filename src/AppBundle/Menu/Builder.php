<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\SecurityContext;

class Builder
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

        if(!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        }else{
            $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        }

        return $menu;
    }
}