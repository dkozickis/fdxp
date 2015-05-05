<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Flightwatch;
use AppBundle\Entity\FlightwatchInfo;
use AppBundle\Form\Type\FlightWatchType;
use AppBundle\Utils\OFPUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FlightWatchController extends Controller
{

    /**
     * @Route("/fw", name="fw_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $flights = $em->getRepository('AppBundle:Flightwatch')->findAllWithInfo();
        $form = $this->createOFPForm();

        foreach ($flights as $fKey => $flight) {

                foreach ($flight['info'] as $key => $info) {

                    $flights[$fKey]['info'][$key]['eto_info'] = 'info';
                    $airportString = '';

                    if($info['airports']) {
                        foreach ($info['airports'] as $airport) {
                            $airportString .= $airport . " ";
                        }
                    }

                    $flights[$fKey]['info'][$key]['airportsString'] = trim($airportString);

                    if (isset($flight['takeOffTime'])) {
                        $takeOffTime = clone $flight['takeOffTime'];
                        $addInterval = new \DateInterval('P0000-00-00T' . $info['eto']->format('H:i:s'));

                        $eto_time = $takeOffTime->add($addInterval);
                        $flights[$fKey]['info'][$key]['eto_time'] = $eto_time;

                        $interval = ($eto_time->getTimestamp() - (new \DateTime("now"))->getTimestamp()) / 60;

                        if ($interval < 30) {
                            $flights[$fKey]['info'][$key]['eto_info'] = 'danger';
                        } elseif ($interval < 60) {
                            $flights[$fKey]['info'][$key]['eto_info'] = 'warning';
                        }
                    }

                }

        }

        return array(
            'flights' => $flights,
            'form' => $form->createView());

    }

    /**
     * @Route("/fw/insertOfp", name="fw_insert_ofp")
     * @Method("POST")
     */
    public function insertAction(Request $request)
    {
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createOFPForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $utils = new OFPUtils();
            $ofp = $data['ofp'];

            $flightInfo = $utils->getMainInfo($ofp);
            $pointInfo = $utils->getETOPSInfo($ofp);
            $dpInfo = $utils->getDPInfo($ofp);

            $fw = new Flightwatch();
            $fw->setFlightNumber($flightInfo['atcCs']);
            $fw->setDep($flightInfo['dep']);
            $fw->setDest($flightInfo['dest']);
            $fw->setFlightDate(new \DateTime($flightInfo['dof']));
            $fw->setStd(new \DateTime($flightInfo['std']));
            $fw->setAltn($flightInfo['altn']);

            $em->persist($fw);

            foreach ($pointInfo as $value) {
                $fwInfo = new FlightwatchInfo();
                $fwInfo->setFlight($fw);
                $fwInfo->setEto(new \DateTime($value['time']));
                $fwInfo->setPointName($value['name']);
                $fwInfo->setPointType('etops');
                $fwInfo->setAirports($value['airports']);
                $em->persist($fwInfo);
            }

            if ($dpInfo) {
                $erdErda = $utils->getErdErda($ofp);
                $fw->setErd($erdErda['erd']);
                $fw->setErda($erdErda['erda']);
                $fwInfo = new FlightwatchInfo();
                $fwInfo->setFlight($fw);
                $fwInfo->setEto(new \DateTime($dpInfo['time']));
                $fwInfo->setPointType('dp');
                $fwInfo->setPointName($dpInfo['name']);
                $fwInfo->setEbo($dpInfo['fob']);
                $em->persist($fwInfo);
            }

            $em->flush();
            $em->clear();

            return $this->redirectToRoute('fw_index');
        } else {

            $flash->alert('Something wrong with the form.');
            return $this->redirectToRoute('fw_index');

        }

    }

    /**
     * Displays a form to edit an existing Flightwatch entity.
     *
     * @Route("/fw/{id}/edit", name="fw_edit")
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var $entity FlightWatch */
        $entity = $em->getRepository('AppBundle:Flightwatch')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Flightwatch entity.');
        }

        if(!$entity->getTakeOffTime()){
            $entity->setTakeOffTime(new \DateTime('today'));
        }

        $editForm = $this->createEditForm($entity);
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/fw/{id}", name="fw_update")
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

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fw_index'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * @Route("/fw/wx", name="fw_wx")
     * @Method("GET")
     */
    public function wxAction(Request $request){

        $airports = $request->query->get('airports');

        $metarXML = file_get_contents('http://weather.aero/dataserver_current/httpparam?'.
            'datasource=metars&requestType=retrieve&format=xml&mostRecentForEachStation=constraint&'.
            'hoursBeforeNow=3&stationString='.urlencode($airports));

        $tafXML = file_get_contents('http://weather.aero/dataserver_current/httpparam?'.
            'datasource=tafs&requestType=retrieve&format=xml&mostRecentForEachStation=constraint&'.
            'hoursBeforeNow=3&stationString='.urlencode($airports));

        $crawler = new Crawler($metarXML);

        $metars = $crawler->filter('raw_text')->extract(array(
            '_text'
        ));

        $crawler = new Crawler($tafXML);

        $tafs = $crawler->filter('raw_text')->extract(array(
            '_text'
        ));


        $response = new Response();
        $response->setContent(json_encode(array(
            'metars' => $metars,
            'tafs' => $tafs
        )));

        //$response->headers->set('Content-Type', 'application/json');

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
        /*$form->get('actions')->add('delete', 'button', array(
            'label' => 'Delete',
            'button_class' => 'danger',
            'attr' => array(
                'id' => 'delete-button',
            ),));*/
        $form->get('actions')->add('backToList', 'button', array(
                'as_link' => true, 'attr' => array(
                    'href' => $this->generateUrl('fw_index'),
                ),
            )
        );

        return $form;
    }

    private function createOFPForm()
    {
        return $this->createFormBuilder(array('ofp' => 'Copy OFP text here'))
            ->setAction($this->generateUrl('fw_insert_ofp'))
            ->add('ofp', 'textarea', array(
                'label' => 'OFP'
            ))
            ->add('Parse', 'submit')
            ->getForm();
    }

}