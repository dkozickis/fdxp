<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\HighSpeedFuel;
use AppBundle\Form\HighSpeedFuelType;

/**
 * HighSpeedFuel controller.
 *
 * @Route("/hifuel")
 */
class HighSpeedFuelController extends Controller
{

    /**
     * Lists all HighSpeedFuel entities.
     *
     * @Route("/", name="highspeedfuel")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:HighSpeedFuel')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new HighSpeedFuel entity.
     *
     * @Route("/", name="highspeedfuel_create")
     * @Method("POST")
     * @Template("AppBundle:HighSpeedFuel:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new HighSpeedFuel();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('highspeedfuel_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a HighSpeedFuel entity.
     *
     * @param HighSpeedFuel $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(HighSpeedFuel $entity)
    {
        $form = $this->createForm(new HighSpeedFuelType(), $entity, array(
            'action' => $this->generateUrl('highspeedfuel_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new HighSpeedFuel entity.
     *
     * @Route("/new", name="highspeedfuel_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new HighSpeedFuel();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a HighSpeedFuel entity.
     *
     * @Route("/{id}", name="highspeedfuel_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:HighSpeedFuel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HighSpeedFuel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing HighSpeedFuel entity.
     *
     * @Route("/{id}/edit", name="highspeedfuel_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:HighSpeedFuel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HighSpeedFuel entity.');
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
    * Creates a form to edit a HighSpeedFuel entity.
    *
    * @param HighSpeedFuel $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(HighSpeedFuel $entity)
    {
        $form = $this->createForm(new HighSpeedFuelType(), $entity, array(
            'action' => $this->generateUrl('highspeedfuel_update', array('id' => $entity->getId())),
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
                'href' => $this->generateUrl('highspeedfuel'),
            ),
        ));

        return $form;
    }
    /**
     * Edits an existing HighSpeedFuel entity.
     *
     * @Route("/{id}", name="highspeedfuel_update")
     * @Method("PUT")
     * @Template("AppBundle:HighSpeedFuel:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:HighSpeedFuel')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HighSpeedFuel entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('highspeedfuel_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a HighSpeedFuel entity.
     *
     * @Route("/{id}", name="highspeedfuel_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:HighSpeedFuel')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HighSpeedFuel entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('highspeedfuel'));
    }

    /**
     * Creates a form to delete a HighSpeedFuel entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('highspeedfuel_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
