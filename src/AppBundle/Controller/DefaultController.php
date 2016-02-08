<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     *
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('AppBundle::layout.html.twig');
    }

    /**
     * @Route("/tools/")
     *
     * @Method({"GET"})
     */
    public function toolsAction()
    {
        return $this->render('AppBundle:Default:placeholder.html.twig', array(
            'pageName' => 'Tools'
        ));
    }
    /**
     * @Route("/time/")
     *
     * @Method({"GET"})
     */
    public function timeAction()
    {
        return $this->render('AppBundle:Default:placeholder.html.twig', array(
            'pageName' => 'Time'
        ));
    }
}
