<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->redirectToRoute('shiftlog_index');
    }
}
