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
     * @Route("/")
     */
    public function oldIndexAction(){

        return $this->redirectToRoute('fw_index');

    }

    /**
     * @Route("/view/{desk}/{dp}", name="fw_index", defaults={"desk":"all", "dp" : 0}, options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($desk, $dp)
    {

        $flights = $this->getDoctrine()->getManager()->getRepository('AppBundle:Flightwatch')->findByDeskWithInfo($desk, $dp);

        $form = $this->createOFPForm($desk);

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
                        $flights[$fKey]['info'][$key]['form'] = $this->createFinalizePointForm($info['id'])->createView();
                    }

                }

            }

            $flights[$fKey]['form'] = $this->createFinalizeFlightForm($flight['id'])->createView();

        }

        return array(
            'flights' => $flights,
            'form' => $form->createView(),
            'desk' => $desk,
            'dp' => $dp
        );

    }

    /**
     * @Route("/archive", name="fw_archive_select")
     * @Method("GET")
     */
    public function archiveSelectAction() {

        return $this->render('AppBundle:FlightWatch:archive.html.twig');

    }

    /**
     * @Route("/archive/view/{date}", name="fw_archive_view", options={"expose"=true})
     * @Method("GET")
     */
    public function archiveViewAction($date) {

        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime($date);

        /*$flights = $em->getRepository('AppBundle:Flightwatch')->findBy(array(
            'flightDate' => $date,
            'completed' => 1
        ));*/

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
                        $flights[$fKey]['info'][$key]['form'] = $this->createFinalizePointForm($info['id'])->createView();
                    }

                }

            }

            $flights[$fKey]['form'] = $this->createFinalizeFlightForm($flight['id'])->createView();

        }


        return $this->render('AppBundle:FlightWatch:archive.html.twig',
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

            return $this->redirectToRoute('fw_index', array(
                'desk' => $formDesk
            ));

        } else {

            $flash->alert('Something wrong with the form.');
            return $this->redirectToRoute('fw_index');

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
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Flightwatch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Flightwatch entity.');
        }

        if (!$entity->getTakeOffTime()) {
            $entity->setTakeOffTime(new \DateTime('today'));
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
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
        $desk = $entity->getDesk();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ComparisonCaseCalc entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fw_index', array(
                'desk' => $desk
            )));
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
        $desk = $entity->getDesk();

        if (!$entity) {
            $flash->alert('Flight was not finalized');
            return $this->redirectToRoute('fw_index');
        }

        $entity->setCompleted(true);
        $entity->setCompletedAt(new \DateTime('now'));
        $entity->setCompletedBy($this->get('security.token_storage')->getToken()->getUsername());

        $em->persist($entity);
        $em->flush();

        $flash->success('Flight finalized');
        return $this->redirectToRoute('fw_index', array(
            'desk' => $desk
        ));
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
        $desk = $entity->getFlight()->getDesk();

        if (!$entity) {
            $flash->alert('Point was not finalized');
            return $this->redirectToRoute('fw_index');
        }

        $entity->setCompleted(true);
        $entity->setCompletedAt(new \DateTime('now'));
        $entity->setCompletedBy($this->get('security.token_storage')->getToken()->getUsername());

        $em->persist($entity);
        $em->flush();

        $flash->success('Point finalized');
        return $this->redirectToRoute('fw_index', array(
            'desk' => $desk
        ));

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

    private function createEditForm(Flightwatch $entity)
    {

        $form = $this->createForm(new FlightWatchType(), $entity, array(
            'action' => $this->generateUrl('fw_update', array(
                'id' => $entity->getId())),
            'method' => 'PUT'
        ));

        $form->add('actions', 'form_actions');

        $form->get('actions')->add('submit', 'submit', array('label' => 'Update'));
        $form->get('actions')->add('backToList', 'button', array(
                'as_link' => true, 'attr' => array(
                    'href' => $this->generateUrl('fw_index'),
                ),
            )
        );

        return $form;
    }

    private function createOFPForm($desk)
    {
        return $this->createFormBuilder(array('ofp' => 'Copy OFP text here'))
            ->setAction($this->generateUrl('fw_insert_ofp'))
            ->add('ofp', 'textarea', array(
                'label' => 'OFP'
            ))
            ->add('desk', 'hidden', array(
                'data' => $desk
            ))
            ->add('Parse', 'submit')
            ->getForm();
    }

    private function createFinalizeFlightForm($id)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fw_finalize_flight', array(
                'id' => $id
            )))
            ->getForm();
    }

    private function createFinalizePointForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fw_finalize_point', array(
                'id' => $id
            )))
            ->getForm();
    }

}
