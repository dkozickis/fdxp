<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ShiftLogArchive;
use AppBundle\Entity\ShiftLogFiles;
use AppBundle\Form\Type\ShiftLogFileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ShiftLog;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
     */
    public function indexAction(Request $request)
    {
        $shift_summary = [];
        $shift_text = '';
        $proposer = $this->get('app.app_utils')->archiveDateShiftProposal();
        $showButton = $this->get('app.app_utils')->showArchiveButton();

        $file = new ShiftLogFiles();

        $form = $this->createForm(new ShiftLogFileType(), $file);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $form->getData();

            $file->upload();

            $em->persist($file);
            $em->flush();
        }

        $info_result = $this->getDoctrine()->getRepository('AppBundle:ShiftLog')->returnAllOrdered();

        if (!$info_result) {
            throw $this->createNotFoundException('Unable to find ShiftLog entity.');
        }

        if (!empty($proposer['date'])) {
            $shift_text = strtoupper($proposer['date']->format('dMy')).' '.$proposer['shift'];
        }

        if ($showButton === 0) {
            $shift_summary['menu_state'] = 'hidden';
        } else {
            $shift_summary['menu_state'] = '';
        }

        return $this->render('AppBundle:ShiftLog:index.html.twig', array(
            'information' => $info_result,
            'shift_summary' => $shift_summary,
            'shift_text' => $shift_text,
            'form' => $form->createView(),
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
            $entities = $em->getRepository('AppBundle:ShiftLog')->findAllCurrent();

            foreach ($entities as $entity) {
                $shiftLogArchive = new ShiftLogArchive();
                $shiftLogArchive->insertArchive($entity['content'], $entity['infoType'], $entity['infoHeader'],
                    $this->getUser()->getUsername(), $proposer['shift'], $proposer['date']);
                $em->persist($shiftLogArchive);
            }

            $em->flush();

            $flash->success(strtoupper($proposer['date']->format('dMy')).' '.$proposer['shift'].' shift archived!');

            return $this->redirectToRoute('shiftlog_index');
        }
    }

    /**
     * @return Response
     * @Route("/timecheck", name="shiftlog_timecheck")
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
     * @Route("/upload", name="shiftlog_file_upload")
     */
    public function uploadAction(Request $request)
    {
        $file = new ShiftLogFiles();

        $form = $this->createFormBuilder($file)
            ->add('name')
            ->add('file')
            ->add('upload', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $form->getData();

            $file->upload();

            $em->persist($file);
            $em->flush();

            return $this->redirectToRoute('shiftlog_index');
        }

        dump($form->getErrors());

        return $this->redirectToRoute('shiftlog_index');
    }
}
