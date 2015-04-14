<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ShiftLogArchive;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ShiftLog;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * ShiftLog controller.
 * @Route("/shiftlog")
 */
class ShiftLogController extends Controller
{

    /**
     * Lists all ShiftLog entities.
     * @Route("/", name="shiftlog_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ShiftLog')->findAllCurrent();

        if (!$entities) {
            throw $this->createNotFoundException('Unable to find ShiftLog entity.');
        }

        foreach($entities as $entity){
            $info_result[] = array('content' => $entity['content'],
                                                        'info_header' => $entity['infoHeader'],
                                                        'info_type' => $entity['infoType']);
        }

        $shift_summary = [];
        $shift_text = '';

        $proposer = $this->get('app.app_utils')->archiveDateShiftProposal();

        if(!empty($proposer['date'])){
            $shift_text = strtoupper($proposer['date']->format('dMy'))." ".$proposer['shift'];
        }

        $showButton = $this->get('app.app_utils')->showArchiveButton();

        if($showButton === 0){
            $shift_summary['menu_state'] = 'hidden';
        }else{
            $shift_summary['menu_state'] = '';
        }

        return $this->render('AppBundle:ShiftLog:index.html.twig', array(
            'information' => $info_result,
            'shift_summary' => $shift_summary,
            'shift_text' => $shift_text
        ));
    }

    /**
     * Edits an existing ShiftLog entity.
     * @Route("/update/{type}", name="shiftlog_update", defaults={"type" = ""})
     * @Method({"POST","PUT"})
     */
    public function updateAction(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ShiftLog')->findOneBy(array('infoType' => $type));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShiftLog entity.');
        }

        $entity->setContent($request->request->get('content'));
        $em->flush();

        return new Response();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/archive", name="shiftlog_archive")
     * @Method({"GET", "POST"})
     */
    public function archiveAction()
    {
        $flash = $this->get('braincrafted_bootstrap.flash');
        $session = new Session();
        $session->getFlashBag()->clear();

        $time_now = new \DateTime("now");
        $hours_now = $time_now->format('G');

        $proposer = $this->get('app.app_utils')->archiveDateShiftProposal($hours_now);

        if(!empty($proposer['date']))
        {
            $archivedDate = $proposer['date'];
            $archivedShift = $proposer['shift'];
        }
        else
        {
            $flash->alert('You are not within archiving time limits!');
            return $this->redirectToRoute('shiftlog_index');
        }

        $em = $this->getDoctrine()->getManager();

        if($em->getRepository('AppBundle:ShiftLogArchive')->checkExistsShiftReport($archivedDate, $archivedShift))
        {
            $flash->alert(strtoupper($archivedDate->format('dMy'))." ".$archivedShift." shift already archived!");

            return $this->redirectToRoute('shiftlog_index');
        }
        else
        {
            $entities = $em->getRepository('AppBundle:ShiftLog')->findAllCurrent();

            foreach($entities as $entity)
            {
                $shiftLogArchive = new ShiftLogArchive();
                $shiftLogArchive->setContent($entity['content']);
                $shiftLogArchive->setInfoType($entity['infoType']);
                $shiftLogArchive->setInfoHeader($entity['infoHeader']);
                $shiftLogArchive->setArchivedBy($this->getUser()->getUsername());
                $shiftLogArchive->setArchivedShift($archivedShift);
                $shiftLogArchive->setArchivedDate($archivedDate);
                $em->persist($shiftLogArchive);
            }

            $em->flush();

            $flash->success(strtoupper($archivedDate->format('dMy'))." ".$archivedShift." shift archived!");

            return $this->redirectToRoute('shiftlog_index');
        }


    }

    /**
     * @return Response
     * @Route("/timecheck", name="shiftlog_timecheck")
     */
    public function archiveTimeCheckAction(){

        $activate = $this->get('app.app_utils')->showArchiveButton();

        $response = new Response();
        $response->setContent(json_encode(array(
            'activate' => $activate,
        )));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
