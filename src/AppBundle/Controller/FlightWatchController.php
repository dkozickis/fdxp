<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Flightwatch;
use AppBundle\Entity\FlightwatchInfo;
use AppBundle\Form\Type\FlightWatchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FlightWatchController
 * @package AppBundle\Controller
 * @Route("/fw")
 */
class FlightWatchController extends Controller
{

    /**
     * @Route("/view/desk/{desk}/filterDP/{dp}/{print}", name="fw_index",
    defaults={"desk" : "all", "dp" : 0, "dpSort" : 0, "print" : "no"}, options={"expose"=true})
     * @Route("/view/")
     * @Route("/view")
     * @Route("/")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction($desk = 'all', $dp = '0', $print = null)
    {

        $flights = $this->getDoctrine()->getManager()->getRepository('AppBundle:Flightwatch')->findByDeskWithInfoObject(
            $desk,
            $dp
        );

        $OFPForm = $this->createOFPForm($desk);

        /** @var Flightwatch $flight */
        foreach ($flights as $flight) {

            /** @var FlightwatchInfo $flightInfo */
            foreach ($flight->getInfo() as $flightInfo) {

                $flightInfo->setAirportsString(
                    $this->get('app.wx_utils')->generateAirportString($flightInfo->getAirports())
                );

                if ($flight->getTakeOffTime() !== null) {

                    $takeOffTime = \DateTimeImmutable::createFromMutable($flight->getTakeOffTime());
                    $addInterval = new \DateInterval('P0000-00-00T'.$flightInfo->getEto()->format('H:i:s'));

                    $etoTime = $takeOffTime->add($addInterval);
                    $flightInfo->setEtoTime($etoTime);

                    if ($flightInfo->getCompleted() !== null) {
                        $flightInfo->setEtoInfo('success');
                    } else {
                        $interval = ($etoTime->getTimestamp() - (new \DateTime("now"))->getTimestamp()) / 60;
                        $flightInfo->setEtoInfo($this->get('app.fw_utils')->dangerOrWarning($interval));
                        $flightInfo->setForm($this->createFinalizePointForm($flightInfo->getId())->createView());
                    }

                }

            }

            $flight->setForm($this->createFinalizeFlightForm($flight->getId())->createView());
            $flight->setDeleteForm($this->createDeleteFlightForm($flight->getId())->createView());

        }

        if ($print === "print") {

            $html = $this->renderView(
                'AppBundle:FlightWatch:index_print.html.twig',
                array(
                    'flights' => $flights,
                    'form' => $OFPForm->createView(),
                    'desk' => $desk,
                    'dp' => $dp
                )
            );

            return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml(
                    $html,
                    array('orientation' => 'Landscape')
                ),
                200,
                array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="Flightwatch_Desk_'.$desk.'_'.date(
                            "d_m_Y_Hi\\Z"
                        ).'.pdf"'
                )
            );


        } else {


            return array(
                'flights' => $flights,
                'form' => $OFPForm->createView(),
                'desk' => $desk,
                'dp' => $dp
            );
        }

    }

    private function createOFPForm($desk)
    {
        return $this->createFormBuilder(array('ofp' => 'Copy OFP text here'))
            ->setAction($this->generateUrl('fw_insert_ofp'))
            ->add(
                'ofp',
                'textarea',
                array(
                    'label' => 'OFP'
                )
            )
            ->add(
                'desk',
                'hidden',
                array(
                    'data' => $desk
                )
            )
            ->add('Parse', 'submit')
            ->getForm();
    }

    private function createFinalizePointForm($id)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'fw_finalize_point',
                    array(
                        'id' => $id
                    )
                )
            )
            ->getForm();
    }

    private function createFinalizeFlightForm($id)
    {

        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'fw_finalize_flight',
                    array(
                        'id' => $id
                    )
                )
            )
            ->getForm();
    }

    private function createDeleteFlightForm($id)
    {

        return $this->createFormBuilder(
            null,
            array(
                'attr' => array('id' => 'delete-flight-form-'.$id,
                    'class' => 'delete-flight-form')
            )
        )
            ->setAction(
                $this->generateUrl(
                    'fw_delete_flight',
                    array(
                        'id' => $id
                    )
                )
            )
            ->add(
                'button',
                'button',
                array(
                    'label' => ' ',
                    'button_class' => 'default',
                    'attr' => array(
                        'class' => 'btn-lg btn-block',
                        'icon' => 'remove'
                    )
                )
            )
            ->getForm();
    }

    /**
     * @Route("/archive", name="fw_archive_select")
     * @Method("GET")
     */
    public function archiveSelectAction()
    {

        return $this->render('AppBundle:FlightWatch:archive.html.twig');

    }

    /**
     * @Route("/archive/view/{date}", name="fw_archive_view", options={"expose"=true})
     * @Method("GET")
     */
    public function archiveViewAction($date)
    {

        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime($date);

        $flights = $em->getRepository('AppBundle:Flightwatch')->findCompletedByDate($date);

        foreach ($flights as $fKey => $flight) {

            foreach ($flight['info'] as $key => $info) {

                $flights[$fKey]['info'][$key]['eto_info'] = 'info';
                $flights[$fKey]['info'][$key]['airportsString'] = '';

                if ($info['airports']) {
                    $flights[$fKey]['info'][$key]['airportsString'] =
                        $this->get('app.wx_utils')->generateAirportString($info['airports']);
                }

                if (isset($flight['takeOffTime'])) {
                    $takeOffTime = clone $flight['takeOffTime'];
                    $addInterval = new \DateInterval('P0000-00-00T'.$info['eto']->format('H:i:s'));

                    $eto_time = $takeOffTime->add($addInterval);
                    $flights[$fKey]['info'][$key]['eto_time'] = $eto_time;

                    $interval = ($eto_time->getTimestamp() - (new \DateTime("now"))->getTimestamp()) / 60;

                    $flights[$fKey]['info'][$key]['eto_info'] = $this->get('app.fw_utils')->dangerOrWarning($interval);

                    if ($info['completed']) {
                        $flights[$fKey]['info'][$key]['eto_info'] = 'success';
                    } else {
                        $flights[$fKey]['info'][$key]['form'] = $this->createFinalizePointForm($info['id'])->createView(
                        );
                    }

                }

            }

            $flights[$fKey]['form'] = $this->createFinalizeFlightForm($flight['id'])->createView();

        }


        return $this->render(
            'AppBundle:FlightWatch:archive.html.twig',
            array(
                'flights' => $flights,
                'date' => $date
            )
        );

    }

    /**
     * @Route("/insertOfp/{desk}", name="fw_insert_ofp", defaults={"desk" : 1})
     * @Method("POST")
     */
    public function insertAction(Request $request, $desk)
    {
        $flash = $this->get('braincrafted_bootstrap.flash');
        $form = $this->createOFPForm($desk);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $ofpUtils = $this->get('app.ofp_utils');
            $fwUtils = $this->get('app.fw_utils');
            $ofp = $form->getData()['ofp'];
            $formDesk = $form->getData()['desk'];

            $flightInfo = $ofpUtils->getMainInfo($ofp);
            $pointInfo = $ofpUtils->getETOPSInfo($ofp);
            $dpInfo = $ofpUtils->getDPInfo($ofp);
            $erdErda = $ofpUtils->getErdErda($ofp);

            $fwUtils->addNewFlight($flightInfo, $pointInfo, $dpInfo, $erdErda, $formDesk);

            return $this->redirect($this->get('request')->headers->get('referer'));

        } else {

            $flash->alert('Something wrong with the form.');

            return $this->redirect($this->get('request')->headers->get('referer'));

        }

    }

    /**
     * Displays a form to edit an existing Flightwatch entity.
     *
     * @Route("/flight/{id}/edit", name="fw_edit")
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Flightwatch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Flightwatch entity.');
        }

        $editForm = $this->createEditForm($entity, $request);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    private function createEditForm(Flightwatch $entity, Request $request)
    {

        $form = $this->createForm(
            new FlightWatchType($request),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'fw_update',
                    array(
                        'id' => $entity->getId()
                    )
                ),
                'method' => 'PUT'
            )
        );

        $form->add('actions', 'form_actions');

        $form->get('actions')->add('submit', 'submit', array('label' => 'Update'));
        $form->get('actions')->add(
            'backToList',
            'button',
            array(
                'as_link' => true,
                'attr' => array(
                    'href' => $this->generateUrl('fw_index'),
                ),
            )
        );

        return $form;
    }

    /**
     * @Route("/{id}", name="fw_update")
     *
     * @Method("PUT")
     * @Template("AppBundle:Flightwatch:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Flightwatch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $editForm = $this->createEditForm($entity, $request);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($editForm->get('ref')->getData());
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * @Route("/finalize/{id}", name="fw_finalize_flight")
     *
     * @Method("POST")
     */
    public function finalizeFlightAction($id)
    {

        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Flightwatch')->find($id);

        if (!$entity) {
            $flash->alert('Flight was not finalized');

            return $this->redirect($this->get('request')->headers->get('referer'));
        }

        $entity->setCompleted(true);
        $entity->setCompletedAt(new \DateTime('now'));
        $entity->setCompletedBy($this->get('security.token_storage')->getToken()->getUsername());

        $em->persist($entity);
        $em->flush();

        $flash->success('Flight finalized');

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

    /**
     * @Route("/delete/{id}", name="fw_delete_flight")
     *
     * @Method("POST")
     */
    public function deleteFlightAction($id)
    {

        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Flightwatch')->find($id);

        if (!$entity) {
            $flash->alert('Flight was NOT deleted');

            return $this->redirect($this->get('request')->headers->get('referer'));
        }

        $em->remove($entity);
        $em->flush();

        $flash->success('Flight DELETED');

        return $this->redirect($this->get('request')->headers->get('referer'));
    }

    /**
     * @param $id
     * @Route("/finalize/point/{id}", name="fw_finalize_point")
     *
     * @Method("POST")
     */
    public function finalizePointAction($id)
    {

        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();

        /** @var  $entity FlightwatchInfo */

        $entity = $em->getRepository('AppBundle:FlightwatchInfo')->find($id);

        if (!$entity) {
            $flash->alert('Point was not finalized');

            return $this->redirect($this->get('request')->headers->get('referer'));
        }

        $entity->setCompleted(true);
        $entity->setCompletedAt(new \DateTime('now'));
        $entity->setCompletedBy($this->get('security.token_storage')->getToken()->getUsername());

        $em->persist($entity);
        $em->flush();

        $flash->success('Point finalized');

        return $this->redirect($this->get('request')->headers->get('referer'));

    }

    /**
     * @Route("/wx/{id}/{force}", name="fw_wx", defaults={"force": 0}, options={"expose"=true})
     * @Method({"GET"})
     */
    public function wxAction($id, $force)
    {
        $em = $this->getDoctrine()->getManager();
        $wxUtils = $this->get('app.wx_utils');

        $fwInfo = $em->getRepository('AppBundle:FlightwatchInfo')->find($id);

        $airports = $fwInfo->getAirports();
        $wxInfoDB = $fwInfo->getWxInfo();

        if (empty($wxInfoDB) || $force == 1) {

            $wxInfo = array(
                'metars' => $wxUtils->getMetars($airports),
                'tafs' => $wxUtils->getTafs($airports),
                'time' => strtoupper((new \DateTime('now'))->format('dM H:i\Z'))
            );

            $fwInfo->setWxInfoAndTime($wxInfo, new \DateTime('now'));
            $em->persist($fwInfo);
            $em->flush();

        } else {

            $wxInfo = $wxInfoDB;

        }


        $response = new Response();
        $response->setContent(json_encode($wxInfo));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}
