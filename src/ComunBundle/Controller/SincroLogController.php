<?php

namespace ComunBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use UniqueConstraintViolationException;
use ComunBundle\Datatables\SincroLogDatatable;

/**
 * Class SincroLogController
 * @package ComunBundle\Controller
 */
class SincroLogController extends Controller {

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $sesion;

    /**
     * SincroLogController constructor.
     */
    public function __construct() {
        $this->sesion = new Session();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function queryAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $isAjax = $request->isXmlHttpRequest();

        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $datatable = $this->get('sg_datatables.factory')->create(SincroLogDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            if ($Usuario->getPerfil() == 'ROLE_ADMIN') {
                $datatableQueryBuilder->buildQuery();
            } else {
                $qb = $datatableQueryBuilder->getQb();
                $qb->andWhere('usuario = :usuario');
                $qb->setParameter('usuario', $Usuario);
            }
            return $responseService->getResponse();
        }

        return $this->render('comun/logs/query.html.twig', ['datatable' => $datatable]);
    }

    public function descargaAction($id) {

        $SincroLog = $this->getDoctrine()->getManager()->getRepository("ComunBundle:SincroLog")->find($id);

        $filename = $SincroLog->getFicherolog();

        $response = new Response();
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=1');
        $response->setContent(file_get_contents($filename));

        return $response;
    }

}
