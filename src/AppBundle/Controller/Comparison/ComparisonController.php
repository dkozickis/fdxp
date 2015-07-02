<?php

namespace AppBundle\Controller\Comparison;

use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\LineString;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Comparison;
use AppBundle\Form\Type\ComparisonType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Comparison controller.
 *
 * @Route("/compare")
 */
class ComparisonController extends Controller
{
    /**
     * Lists all Comparison entities.
     *
     * @Route("/", name="compare")
     *
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Comparison')->findAll();

        return $this->render('AppBundle:Comparison:index.html.twig', array(
            'entities' => $entities,
        ));

    }

    /**
     * @Route("/{comp_id}/summary", name="compare_summary")
     *
     * @Method("GET")
     */
    public function summaryAction($comp_id)
    {
        $utils = $this->get('app.comparison_utils');
        $allCalcs = [];
        $header = [''];
        $i = 0;

        $comparison = $this->getDoctrine()->getRepository('AppBundle:Comparison')->find($comp_id);
        $casesCalcs = $this->getDoctrine()->getRepository('AppBundle:ComparisonCase')->findCaseCalcs($comp_id);
        $caseCount = count($casesCalcs);

        foreach ($casesCalcs as $case) {
            $header = $utils->summaryHeaderBuilder($header, $case);

            foreach ($case['calcs'] as $calc) {
                if (!$case['basic'] && isset($allCalcs[$calc['citypair']][0])) {
                    $cost_diff = $calc['cost'] - $allCalcs[$calc['citypair']][0]['cost'];
                    $time_diff = $allCalcs[$calc['citypair']][0]['time']->diff($calc['time']);
                } else {
                    $cost_diff = 0;
                    $time_diff = new \DateInterval('P0M');
                }
                $allCalcs[$calc['citypair']][$i] = array(
                    'basic' => ($case['basic'] ? 1 : 0),
                    'cost' => $calc['cost'],
                    'time' => $calc['time'],
                    'cost_diff' => $cost_diff,
                    'time_diff' => $time_diff,
                    'dummy' => 0,
                );
            }
            ++$i;
        }

        foreach ($allCalcs as $key => $value) {
            if (count($value) != $caseCount) {
                for ($i = 0; $i < $caseCount; $i++) {
                    if (!isset($allCalcs[$key][$i])) {
                        $allCalcs[$key][$i] = array(
                            'basic' => ($i == 0 ? 1 : 0),
                            'cost' => 0,
                            'time' => 0,
                            'cost_diff' => 0,
                            'time_diff' => new \DateInterval('P0M'),
                            'dummy' => 1,
                        );
                    }
                }
            }
            ksort($allCalcs[$key]);
        }


        return $this->render('AppBundle:Comparison:summary.html.twig', array(
            'calc_info' => $allCalcs,
            'header' => $header,
            'counter' => $caseCount,
            'comparison' => $comparison
        ));
    }

    /**
     * @Route("/{comp_id}/route", name="compare_route")
     *
     * @Method("GET")
     */
    public function jsonRouteAction($comp_id)
    {

        $features = [];

        $cases = $this->getDoctrine()->getManager()->getRepository('AppBundle:ComparisonCase')->findBy(array(
            'comparison' => $comp_id
        ));

        foreach ($cases as $case) {
            $calcs = $case->getCalcs();
            foreach ($calcs as $calc) {
                $route = $calc->getRoute();
                if (trim($route) != '') {

                    $coords = $this->get('app.comparison_utils')->atcToLatLon($route);

                    $features[] = new Feature(new LineString($coords), array(
                        'color'=> sprintf('#%06X', mt_rand(0, 0xFFFFFF))
                    ));

                }
            }
        }

        $geoJSON = new FeatureCollection($features);
        $response = new Response(json_encode($geoJSON));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Creates a new Comparison entity.
     *
     * @Route("/", name="compare_create")
     *
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new Comparison();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('compare'));
        }

        return $this->render('AppBundle:Comparison:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Comparison entity.
     *
     * @param Comparison $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comparison $entity)
    {
        $form = $this->createForm(new ComparisonType(), $entity, array(
            'action' => $this->generateUrl('compare_create'),
            'method' => 'POST',
        ));

        $form->add('actions', 'form_actions');

        $form->get('actions')->add('submit', 'submit', array('label' => 'Create'));
        $form->get('actions')->add('backToList', 'button', array(
            'as_link' => true, 'attr' => array(
                'href' => $this->generateUrl('compare'),
            ),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Comparison entity.
     *
     * @Route("/new", name="compare_new")
     *
     * @Method("GET")
     */
    public function newAction()
    {
        $entity = new Comparison();
        $form = $this->createCreateForm($entity);

        return $this->render('AppBundle:Comparison:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comparison entity.
     *
     * @Route("/{id}", name="compare_show")
     *
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Comparison')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comparison entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Comparison:show.html.twig', array(

            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comparison entity.
     *
     * @Route("/{id}/edit", name="compare_edit")
     *
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Comparison')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comparison entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);


        return $this->render('AppBundle:Comparison:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Comparison entity.
     *
     * @param Comparison $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Comparison $entity)
    {
        $form = $this->createForm(new ComparisonType(), $entity, array(
            'action' => $this->generateUrl('compare_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('actions', 'form_actions');

        $form->get('actions')->add('submit', 'submit', array('label' => 'Update'));
        $form->get('actions')->add('delete', 'button', array(
            'label' => 'Delete',
            'button_class' => 'danger',
            'attr' => array(
                'id' => 'delete-button',
            ),));
        $form->get('actions')->add('backToList', 'button', array(
            'as_link' => true, 'attr' => array(
                'href' => $this->generateUrl('compare'),
            ),
        ));

        return $form;
    }

    /**
     * Edits an existing Comparison entity.
     *
     * @Route("/{id}", name="compare_update")
     *
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Comparison')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comparison entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compare_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:Comparison:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comparison entity.
     *
     * @Route("/{id}", name="compare_delete")
     *
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Comparison')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comparison entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compare'));
    }

    /**
     * Creates a form to delete a Comparison entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compare_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }
}
