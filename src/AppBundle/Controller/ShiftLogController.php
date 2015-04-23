<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ShiftLogFiles;
use AppBundle\Form\Type\ShiftLogFileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
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
     *
     * @Cache(expires="+15 min")
     */
    public function indexAction(Request $request)
    {
        $shift_info = $this->get('app.app_utils')->mainePageInit();
        $shift_files = $this->getDoctrine()->getRepository('AppBundle:ShiftLogFiles')->findAll();

        $file = new ShiftLogFiles();
        $form = $this->createForm(new ShiftLogFileType(), $file);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            return $this->redirectToRoute('shiftlog_index');
        }

        $info_result = $this->getDoctrine()->getRepository('AppBundle:ShiftLog')->returnAllOrdered();

        return $this->render('AppBundle:ShiftLog:index.html.twig', array(
            'information' => $info_result,
            'shift_info' => $shift_info,
            'form' => $form->createView(),
            'files' => $shift_files,
        ));
    }

    /**
     * Edits an existing ShiftLog entity.
     *
     * @Route("/update/{type}", name="shiftlog_update", defaults={"type" = ""})
     *
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
     *
     * @Method({"GET", "POST"})
     */
    public function archiveAction()
    {
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
                $proposer['shift'], $proposer['date']);

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
     * @Method({"GET"})
     */
    public function deleteFileAction($id)
    {
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:ShiftLogFiles')->findOneBy(array('id' => $id));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find this file record.');
        }

        $em->remove($entity);
        $em->flush();

        $flash->success('File removed');

        return $this->redirectToRoute('shiftlog_index');
    }
}
