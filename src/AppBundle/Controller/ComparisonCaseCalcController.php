<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ComparisonCaseCalc;
use AppBundle\Form\Type\ComparisonCaseCalcType;
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
     * @Template()
     */
    public function indexAction(Request $request, $case_id)
    {
        $form = $this->createPlatoForm($case_id);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $utils = new ComparisonUtils();
            $platoArray = $utils->platoToArray($data['plato']);
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ComparisonCase')->find($case_id)->getCalcs();

        return array(
            'entities' => $entities,
            'plato_form' => $form->createView(),
        );
    }

    /**
     * Creates a new ComparisonCaseCalc entity.
     *
     * @Route("/plato", name="comparison_case_calc_plato")
     *
     * @Method("POST")
     * @Template("AppBundle:ComparisonCaseCalc:new.html.twig")
     */
    public function platoAction(Request $request, $case_id)
    {
        $platoArray = [];
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

        dump($platoArray);

        throw new \Exception('Yea');
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
     * @Template("AppBundle:ComparisonCaseCalc:new.html.twig")
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

            return $this->redirect($this->generateUrl('comparison_case_calc_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
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
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ComparisonCaseCalc entity.
     *
     * @Route("/new", name="comparison_case_calc_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction($case_id)
    {
        $entity = new ComparisonCaseCalc();
        $form   = $this->createCreateForm($entity, $case_id);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ComparisonCaseCalc entity.
     *
     * @Route("/{id}", name="comparison_case_calc_show")
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ComparisonCaseCalc entity.
     *
     * @Route("/{id}/edit", name="comparison_case_calc_edit")
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
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
     * Creates a form to edit a ComparisonCaseCalc entity.
     *
     * @param ComparisonCaseCalc $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ComparisonCaseCalc $entity)
    {
        $form = $this->createForm(new ComparisonCaseCalcType(), $entity, array(
            'action' => $this->generateUrl('comparison_case_calc_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ComparisonCaseCalc entity.
     *
     * @Route("/{id}", name="comparison_case_calc_update")
     *
     * @Method("PUT")
     * @Template("AppBundle:ComparisonCaseCalc:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCaseCalc')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('comparison_case_calc_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ComparisonCaseCalc entity.
     *
     * @Route("/{id}", name="comparison_case_calc_delete")
     *
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
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

        return $this->redirect($this->generateUrl('comparison_case_calc'));
    }

    /**
     * Creates a form to delete a ComparisonCaseCalc entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comparison_case_calc_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
