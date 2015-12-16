<?php

namespace AppBundle\Utils;

use AppBundle\Entity\FlightWatch;
use AppBundle\Entity\FlightWatchInfo;
use AppBundle\Form\Type\FlightWatch\FlightWatchDeleteFlightType;
use AppBundle\Form\Type\FlightWatch\FlightWatchFinalizeFlightType;
use AppBundle\Form\Type\FlightWatch\FlightWatchFinalizePointType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;

class FWUtils
{

    private $managerRegistry;

    /**
     * FWUtils constructor.
     * @param ManagerRegistry $managerRegistry
     * @param WXUtils $WXUtils
     * @param FormFactory $formFactory
     * @param Router $router
     * @param LoggableGenerator $knpSnappyPDF
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        WXUtils $WXUtils,
        FormFactory $formFactory,
        Router $router,
        LoggableGenerator $knpSnappyPDF
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->WXUtils = $WXUtils;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->knpSnappyPDF = $knpSnappyPDF;
    }

    /**
     * @param $flightInfo
     * @param $pointInfo
     * @param $dpInfo
     * @param $erdErda
     * @param $desk
     * @return bool
     */
    public function addNewFlight($flightInfo, $pointInfo, $dpInfo, $erdErda, $desk)
    {

        $em = $this->managerRegistry->getManager();
        $fw = new FlightWatch();
        $fw->setMainData($flightInfo, $desk);
        $em->persist($fw);

        foreach ($pointInfo as $value) {
            $fwInfo = new FlightWatchInfo();
            $fwInfo->setPointInfo($fw, $value);
            $em->persist($fwInfo);
        }

        if ($dpInfo) {

            $airports = array($flightInfo['dest']);

            if ($flightInfo['altn'] != 'NIL') {
                $airports[] = $flightInfo['altn'];
            }

            if (isset($erdErda['erd'])) {
                $airports[] = $erdErda['erd'];
            }

            if (isset($erdErda['erda'])) {
                $airports[] = $erdErda['erda'];
            }

            $fw->setErdErda($erdErda['erd'], $erdErda['erda']);
            $fwInfo = new FlightWatchInfo();
            $fwInfo->setDpInfo($fw, $dpInfo, $airports);
            $em->persist($fwInfo);
        }

        $em->flush();
        $em->clear();

        return true;
    }

    /**
     * @param $flights
     */
    public function prepareFlightsForOutput($flights)
    {
        /** @var FlightWatch $flight */
        foreach ($flights as $flight) {

            /** @var FlightWatchInfo $flightInfo */
            foreach ($flight->getInfo() as $flightInfo) {

                $flightInfo->setAirportsString(
                    $this->WXUtils->generateAirportString($flightInfo->getAirports())
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
                        $flightInfo->setEtoInfo($this->dangerOrWarning($interval));
                        $flightInfo->setForm($this->createFinalizePointFormView($flightInfo->getId()));
                    }

                }

            }

            $flight->setFinalizeForm($this->createFinalizeFlightFormView($flight->getId()));
            $flight->setDeleteForm($this->createDeleteFlightFormView($flight->getId()));

        }
    }

    /**
     * @param $flightID
     * @return \Symfony\Component\Form\FormView
     */
    public function createDeleteFlightFormView($flightID)
    {

        return $this->formFactory->create(new FlightWatchDeleteFlightType($flightID, $this->router))->createView();

    }

    /**
     * @param $flightID
     * @return \Symfony\Component\Form\FormView
     */
    public function createFinalizeFlightFormView($flightID)
    {

        return $this->formFactory->create(new FlightWatchFinalizeFlightType($flightID, $this->router))->createView();

    }

    /**
     * @param $flightID
     * @return \Symfony\Component\Form\FormView
     */
    public function createFinalizePointFormView($flightID)
    {

        return $this->formFactory->create(new FlightWatchFinalizePointType($flightID, $this->router))->createView();

    }



    /**
     * @param $interval
     * @return string
     */
    public function dangerOrWarning($interval)
    {

        $status = 'info';

        if ($interval < 30) {
            $status = 'danger';
        } elseif ($interval < 60) {
            $status = 'warning';
        }

        return $status;
    }

    /**
     * @param $html
     * @param $print
     * @return string
     */
    public function responseContent($html, $print)
    {

        if ($print == 'print') {
            return $this->knpSnappyPDF->getOutputFromHtml(
                $html,
                array('orientation' => 'Landscape')
            );
        } else {
            return $html;
        }

    }

    /**
     * @param $desk
     * @param $print
     * @return array
     */
    public function responseHeaders($desk, $print)
    {
        $headers = array();
        if ($print == 'print') {

            $headers = array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Flightwatch_Desk_'.$desk.'_'.date(
                        "d_m_Y_Hi\\Z"
                    ).'.pdf"'
            );

        }

        return $headers;

    }

}
