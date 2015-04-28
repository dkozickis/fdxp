<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Comparison;
use AppBundle\Form\Type\ComparisonType;

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
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Comparison')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @Route("/{comp_id}/summary", name="compare_summary")
     *
     * @Method("GET")
     * @Template()
     */
    public function summaryAction($comp_id)
    {
        $allCalcs = [];
        $headerBuilder[] = '';
        $cost_diff = 0;
        $time_diff = 0;
        $i = 0;

        $comparison = $this->getDoctrine()->getRepository('AppBundle:Comparison')->find($comp_id);

        $cases_calcs = $this->getDoctrine()->getRepository('AppBundle:ComparisonCase')->findCaseCalcs($comp_id);
        $case_count = count($cases_calcs);

        foreach ($cases_calcs as $case) {
            if ($case['basic']) {
                $headerBuilder[] = 'Basic cost';
                $headerBuilder[] = 'Basic time';
            } else {
                $headerBuilder[] = $case['name'].' cost';
                $headerBuilder[] = $case['name'].' cost difference';
                $headerBuilder[] = $case['name'].' time';
                $headerBuilder[] = $case['name'].' time difference';
            }
            foreach ($case['calcs'] as $calc) {
                if (!$case['basic'] && isset($allCalcs[$calc['citypair']][0])) {
                    $cost_diff = $calc['cost'] - $allCalcs[$calc['citypair']][0]['cost'];
                    $time_diff = $allCalcs[$calc['citypair']][0]['time']->diff($calc['time']);
                } else {
                    $cost_diff = 0;
                    $time_diff = 0;
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
            if (count($value) != $case_count) {
                for ($i = 0; $i < $case_count; $i++) {
                    if (!isset($allCalcs[$key][$i])) {
                        $allCalcs[$key][$i] = array(
                            'basic' => ($i == 0 ? 1 : 0),
                            'cost' => 0,
                            'time' => 0,
                            'cost_diff' => 0,
                            'time_diff' => 0,
                            'dummy' => 1,
                        );
                    }
                }
            }
            ksort($allCalcs[$key]);
        }

        return array(
            'calc_info' => $allCalcs,
            'header' => $headerBuilder,
            'counter' => $case_count,
            'comparison' => $comparison
        );
    }

    /**
     * Creates a new Comparison entity.
     *
     * @Route("/", name="compare_create")
     *
     * @Method("POST")
     * @Template("AppBundle:Comparison:new.html.twig")
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

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
     * @Template()
     */
    public function newAction()
    {
        $entity = new Comparison();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Comparison entity.
     *
     * @Route("/{id}", name="compare_show")
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Comparison')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comparison entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Comparison entity.
     *
     * @Route("/{id}/edit", name="compare_edit")
     *
     * @Method("GET")
     * @Template()
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

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
     * @Template("AppBundle:Comparison:edit.html.twig")
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

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
