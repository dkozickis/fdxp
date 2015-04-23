<?php

namespace AppBundle\Controller;

use AppBundle\Utils\ComparisonUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RouteComparisonController.
 *
 * @Route("/compare")
 */
class ComparisonController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {

        $platoArray = [];

        $form = $this->createFormBuilder(array('plato' => 'Copy PLATO text here'))
            ->add('plato', 'textarea')
            ->add('Parse', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $utils = new ComparisonUtils();
            $platoArray = $utils->platoToArray($data['plato']);
        }

        return $this->render('AppBundle:Comparison:index.html.twig', array(
            'form' => $form->createView(),
            'plato_info' => $platoArray,
        ));
    }
}
