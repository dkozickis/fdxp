<?php

namespace AppBundle\Controller\Comparison;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ComparisonCaseCalc;
use AppBundle\Form\Type\ComparisonCaseCalcType;
use AppBundle\Entity\ComparisonCase;
use AppBundle\Utils\ComparisonUtils;

/**
 * ComparisonCaseCalc controller.
 *
 * @Route("/compare/case/{case_id}/calc")
 */
class ComparisonCaseCalcController extends Controller
{
    /**
     * Lists all ComparisonCaseCalc entities.
     *
     * @Route("/", name="comparison_case_calc")
     *
     * @Method("GET")
     */
    public function indexAction(Request $request, $case_id)
    {
        $form = $this->createPlatoForm($case_id);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ComparisonCase')->find($case_id)->getCalcs();

        return $this->render('AppBundle:ComparisonCaseCalc:index.html.twig', array(
            'entities' => $entities,
            'plato_form' => $form->createView(),
        ));
    }

    /**
     * Creates a new ComparisonCaseCalc entity.
     *
     * @Route("/plato", name="comparison_case_calc_plato")
     *
     * @Method("POST")
     */
    public function platoAction(Request $request, $case_id)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createPlatoForm($case_id);
        $form->handleRequest($request);

        $case = $em->getRepository('AppBundle:ComparisonCase')->find($case_id);

        if ($form->isValid()) {
            $data = $form->getData();
            $utils = new ComparisonUtils();
            $platoArray = $utils->platoToArray($data['plato']);

            foreach ($platoArray as $value) {
                $calc = new ComparisonCaseCalc();
                $calc->setCitypair($value['airport_pair']);
                $calc->setCost($value['t_costs']);
                $calc->setTime(new \DateTime($value['t_time']));
                $calc->setCase($case);
                $em->persist($calc);
            }

            $em->flush();
        }

        return $this->redirectToRoute('comparison_case_calc', array(
            'case_id' => $case_id,
        ));
    }

    private function createPlatoForm($case_id)
    {
        return $this->createFormBuilder(array('plato' => 'Copy PLATO text here'))
            ->setAction($this->generateUrl('comparison_case_calc_plato', array('case_id' => $case_id)))
            ->add('plato', 'textarea')
            ->add('Parse', 'submit')
            ->getForm();
    }

    /**
     * Creates a new ComparisonCaseCalc entity.
     *
     * @Route("/", name="comparison_case_calc_create")
     *
     * @Method("POST")
     */
    public function createAction(Request $request, $case_id)
    {
        $entity = new ComparisonCaseCalc();
        $form = $this->createCreateForm($entity, $case_id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('comparison_case_calc', array(
                'case_id' => $case_id,
                )
            ));
        }

        return $this->render('AppBundle:ComparisonCaseCalc:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ComparisonCaseCalc entity.
     *
     * @param ComparisonCaseCalc $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ComparisonCaseCalc $entity, $case_id)
    {
        $form = $this->createForm(new ComparisonCaseCalcType(), $entity, array(
            'action' => $this->generateUrl('comparison_case_calc_create', array('case_id' => $case_id)),
            'method' => 'POST',
            'case' => $case_id
        ));

        $form->add('actions', 'form_actions');

        $form->get('actions')->add('submit', 'submit', array('label' => 'Create'));
        $form->get('actions')->add('backToList', 'button', array(
            'as_link' => true, 'attr' => array(
                'href' => $this->generateUrl('comparison_case_calc', array('case_id' => $case_id)),
            ),
        ));

        return $form;
    }

    /**
     * Displays a form to create a new ComparisonCaseCalc entity.
     *
     * @Route("/new", name="comparison_case_calc_new")
     *
     * @Method("GET")
     */
    public function newAction($case_id)
    {
        $entity = new ComparisonCaseCalc();
        $form   = $this->createCreateForm($entity, $case_id);

        return $this->render('AppBundle:ComparisonCaseCalc:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ComparisonCaseCalc entity.
     *
     * @Route("/{id}", name="comparison_case_calc_show")
     *
     * @Method("GET")
     */
    public function showAction($id, $case_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $case_id);

        return $this->render('AppBundle:ComparisonCaseCalc:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ComparisonCaseCalc entity.
     *
     * @Route("/{id}/edit", name="comparison_case_calc_edit")
     *
     * @Method("GET")
     */
    public function editAction($id, $case_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $editForm = $this->createEditForm($entity, $case_id);
        $deleteForm = $this->createDeleteForm($id, $case_id);

        return $this->render('AppBundle:ComparisonCaseCalc:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a ComparisonCaseCalc entity.
     *
     * @param ComparisonCaseCalc $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ComparisonCaseCalc $entity, $case_id)
    {
        $form = $this->createForm(new ComparisonCaseCalcType(), $entity, array(
            'action' => $this->generateUrl('comparison_case_calc_update', array(
                'id' => $entity->getId(),
                'case_id' => $case_id,)),
            'method' => 'PUT',
            'case' => $case_id
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
                    'href' => $this->generateUrl('comparison_case_calc', array(
                        'case_id' => $case_id,)),
                ),
            )
        );

        return $form;
    }
    /**
     * Edits an existing ComparisonCaseCalc entity.
     *
     * @Route("/{id}", name="comparison_case_calc_update")
     *
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id, $case_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $case_id);
        $editForm = $this->createEditForm($entity, $case_id);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('comparison_case_calc_edit', array(
                'id' => $id,
                'case_id' => $case_id,)));
        }

        return $this->render('AppBundle:ComparisonCaseCalc:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ComparisonCaseCalc entity.
     *
     * @Route("/{id}", name="comparison_case_calc_delete")
     *
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id, $case_id)
    {
        $form = $this->createDeleteForm($id, $case_id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comparison_case_calc', array(
            'case_id' => $case_id,
        )));
    }

    /**
     * Creates a form to delete a ComparisonCaseCalc entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $case_id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comparison_case_calc_delete', array(
                'id' => $id,
                'case_id' => $case_id,)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
