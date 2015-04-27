<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\ComparisonCase;
use AppBundle\Form\ComparisonCaseType;

/**
 * ComparisonCase controller.
 *
 * @Route("/compare/{comp_id}/case")
 */
class ComparisonCaseController extends Controller
{
    /**
     * Lists all ComparisonCase entities.
     *
     * @Route("/", name="compare_case")
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction($comp_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Comparison')->find($comp_id)->getCases();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ComparisonCase entity.
     *
     * @Route("/", name="compare_case_create")
     *
     * @Method("POST")
     * @Template("AppBundle:ComparisonCase:new.html.twig")
     */
    public function createAction(Request $request, $comp_id)
    {
        $entity = new ComparisonCase();
        $form = $this->createCreateForm($entity, $comp_id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('compare_case_show', array('id' => $entity->getId(),
                'comp_id' => $comp_id, )));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ComparisonCase entity.
     *
     * @param ComparisonCase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ComparisonCase $entity, $comp_id)
    {
        $form = $this->createForm(new ComparisonCaseType(), $entity, array(
            'action' => $this->generateUrl('compare_case_create', array('comp_id' => $comp_id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ComparisonCase entity.
     *
     * @Route("/new", name="compare_case_new")
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction($comp_id)
    {
        $entity = new ComparisonCase();
        $form   = $this->createCreateForm($entity, $comp_id);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ComparisonCase entity.
     *
     * @Route("/{id}", name="compare_case_show")
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction($id, $comp_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCase entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $comp_id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ComparisonCase entity.
     *
     * @Route("/{id}/edit", name="compare_case_edit")
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, $comp_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCase entity.');
        }

        $editForm = $this->createEditForm($entity, $comp_id);
        $deleteForm = $this->createDeleteForm($id, $comp_id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a ComparisonCase entity.
     *
     * @param ComparisonCase $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ComparisonCase $entity, $comp_id)
    {
        $form = $this->createForm(new ComparisonCaseType(), $entity, array(
            'action' => $this->generateUrl('compare_case_update', array('id' => $entity->getId(),
                'comp_id' => $comp_id, )),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ComparisonCase entity.
     *
     * @Route("/{id}", name="compare_case_update")
     *
     * @Method("PUT")
     * @Template("AppBundle:ComparisonCase:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $comp_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ComparisonCase')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCase entity.');
        }

        $deleteForm = $this->createDeleteForm($id, $comp_id);
        $editForm = $this->createEditForm($entity, $comp_id);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compare_case_edit', array('id' => $id, 'comp_id' => $comp_id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ComparisonCase entity.
     *
     * @Route("/{id}", name="compare_case_delete")
     *
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id, $comp_id)
    {
        $form = $this->createDeleteForm($id, $comp_id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ComparisonCase')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ComparisonCase entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compare_case', array('comp_id' => $comp_id)));
    }

    /**
     * Creates a form to delete a ComparisonCase entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $comp_id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compare_case_delete', array('id' => $id, 'comp_id' => $comp_id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
