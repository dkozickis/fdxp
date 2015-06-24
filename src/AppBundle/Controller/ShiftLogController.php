<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ShiftLogFiles;
use AppBundle\Entity\ShiftLogOnShift;
use AppBundle\Form\Type\ShiftLogFileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ShiftLog;
use Symfony\Component\HttpFoundation\Response;

/**
 * ShiftLog controller.
 *
 * @Route("/shiftlog")
 */
class ShiftLogController extends Controller
{
    /**
     * Lists all ShiftLog entities.
     *
     * @Route("/", name="shiftlog_index")
     *
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentShift = $this->get('app.app_utils')->currentShift();

        $shiftInfo = $this->get('app.app_utils')->mainePageInit();
        $shiftFiles = $this->getDoctrine()->getRepository('AppBundle:ShiftLogFiles')->findAll();
        $onShift = $em->getRepository('AppBundle:ShiftLogOnShift')->findOneBy(array(
            'shiftDate' => new \DateTime('now'),
            'shiftPeriod' => $currentShift
        ));

        if(!$onShift){
            $onShift = $this->get('app.app_utils')->getPersonnelOnShift();
        }

        foreach ($shiftFiles as $file) {
            /** @var ShiftLogFiles $file */
            $file->setFileDeleteForm($this->createDeleteFileForm($file->getId())->createView());
        }

        $file = new ShiftLogFiles();
        $form = $this->createForm(new ShiftLogFileType(), $file);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($file);
            $em->flush();

            return $this->redirectToRoute('shiftlog_index');
        }

        $info_result = $this->getDoctrine()->getRepository('AppBundle:ShiftLog')->returnAllOrdered();

        return $this->render('AppBundle:ShiftLog:index.html.twig', array(
            'information' => $info_result,
            'shift_info' => $shiftInfo,
            'form' => $form->createView(),
            'files' => $shiftFiles,
            'onShift' => $onShift->getOnShift()
        ));
    }

    /**
     * @Route("/archive", name="shiftlog_archive_select")
     *
     * @Method("GET")
     */
    public function archiveSelectAction() {

        return $this->render('AppBundle:ShiftLog:archive.html.twig');

    }

    /**
     * @Route("/archive/view/{date}/{shiftName}", name="shiftlog_archive_view", options={"expose"=true})
     *
     * @Method("GET")
     */
    public function archiveViewAction($date, $shiftName) {

        $em = $this->getDoctrine()->getManager();

        $date = new \DateTime($date);

        $archiveInfo = $em->getRepository('AppBundle:ShiftLogArchive')->findOneBy(array(
            'archivedDate' => $date,
            'archivedShift' => $shiftName
        ));
        $onShift = $em->getRepository('AppBundle:ShiftLogOnShift')->findOneBy(array(
            'shiftPeriod' => $shiftName,
            'shiftDate' => $date
        ));

        if (!$archiveInfo) {
            return $this->render('AppBundle:ShiftLog:archive.html.twig', array(
                'date' => $date,
                'shift' => $shiftName
            ));
        } else {

            if (!$onShift) {
                $onShift = $this->get('app.app_utils')->getPersonnelOnShift($date, $shiftName);
            }

            if(!isset($archiveInfo->getContent()['files'])){
                $files = [];
            }else{
                $files = $archiveInfo->getContent()['files'];
            }

            return $this->render('AppBundle:ShiftLog:archive.html.twig', array(
                'information' => $archiveInfo->getContent()['log'],
                'files' => $files,
                'date' => $date,
                'shift' => $shiftName,
                'onShift' => $onShift->getOnShift()
            ));
        }



    }


    /**
     * Edits an existing ShiftLog entity.
     *
     * @Route("/update/{type}", name="shiftlog_update", defaults={"type" = ""}, options={"expose"=true})
     *
     * @Method({"PUT"})
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
     * Edits an existing ShiftLog entity.
     *
     * @Route("/updateOnShift/{type}", name="shiftlog_update_onshift", defaults={"type" = ""}, options={"expose"=true})
     *
     * @Method({"PUT"})
     */
    public function updateOnShiftAction(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $currentShift = $this->get('app.app_utils')->currentShift();

        $onShift = $em->getRepository('AppBundle:ShiftLogOnShift')->findOneBy(array(
            'shiftDate' => new \DateTime('now'),
            'shiftPeriod' => $currentShift
        ));

        if (!$onShift) {
            throw $this->createNotFoundException('Unable to find ShiftLog entity.');
        }

        $onShift->setOnShift(json_decode($request->request->get('newOnShift'), TRUE));
        $em->flush();

        return new Response();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/archive/do", name="shiftlog_archive")
     *
     * @Method({"GET", "POST"})
     */
    public function archiveAction()
    {
        $kernel_dir = $this->get('kernel')->getRootDir();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $hours_now = $this->get('app.app_utils')->currentHours();
        $proposer = $this->get('app.app_utils')->archiveDateShiftProposal($hours_now);

        if (empty($proposer['date'])) {
            $flash->alert('You are not within archiving time limits!');

            return $this->redirectToRoute('shiftlog_index');
        }

        $em = $this->getDoctrine()->getManager();

        if ($em->getRepository('AppBundle:ShiftLogArchive')->checkExistsShiftReport($proposer['date'], $proposer['shift'])) {
            $flash->alert(strtoupper($proposer['date']->format('dMy')).' '.$proposer['shift'].' shift already archived!');

            return $this->redirectToRoute('shiftlog_index');
        } else {
            $em->getRepository('AppBundle:ShiftLogArchive')->moveActiveToArchive($this->getUser()->getUsername(),
                $proposer['shift'], $proposer['date'], $kernel_dir);

            $flash->success(strtoupper($proposer['date']->format('dMy')).' '.$proposer['shift'].' shift archived!');

            return $this->redirectToRoute('shiftlog_index');
        }
    }

    /**
     * @return Response
     * @Route("/timecheck", name="shiftlog_timecheck")
     *
     * @Method({"GET"})
     */
    public function archiveTimeCheckAction()
    {
        $activate = $this->get('app.app_utils')->showArchiveButton();

        $response = new Response();
        $response->setContent(json_encode(array(
            'activate' => $activate,
        )));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/filedelete/{id}", name="shiftlog_filedelete")
     *
     * @Method({"DELETE"})
     */
    public function deleteFileAction(Request $request, $id)
    {
        $form = $this->createDeleteFileForm($id);
        $form->handleRequest($request);
        $flash = $this->get('braincrafted_bootstrap.flash');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ShiftLogFiles')->findOneBy(array('id' => $id));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find this file record.');
            }

            $em->remove($entity);
            $em->flush();

            $flash->success('File removed');
        }

        return $this->redirectToRoute('shiftlog_index');
    }

    private function createDeleteFileForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('shiftlog_filedelete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('delete', 'submit', array(
                'button_class' => 'danger btn-xs'
            ))
            ->getForm();
    }
}
