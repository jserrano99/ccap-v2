<?php

namespace ComunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use ComunBundle\Entity\Dependencia;
use ComunBundle\Entity\CargaInicial;
use ComunBundle\Form\CargaInicialType;
use ComunBundle\Form\DependenciaType;
use ComunBundle\Datatables\DependenciaDatatable;


/**
 * Class CargaInicialController
 * @package ComunBundle\Controller
 */
class CargaInicialController extends Controller {

    private $sesion;

    /**
     * CargaInicialController constructor.
     */
    public function __construct() {
        $this->sesion = new Session();
    }

    /**
     * @param Request $request
     * @param $cargaInicial_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addDependenciaAction(Request $request, $cargaInicial_id) {
        $CargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->find($cargaInicial_id);
        $Dependencia = new Dependencia();
        $Dependencia->setCargaInicialDep($CargaInicial);

        $form = $this->createForm(DependenciaType::class, $Dependencia);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($Dependencia);
            $CargaInicial->setNumDep($CargaInicial->getNumDep() + 1);
            $this->getDoctrine()->getManager()->persist($CargaInicial);
            $this->getDoctrine()->getManager()->flush();
            $params=["cargaInicial_id" => $cargaInicial_id];
            return $this->redirectToRoute("queryDependencia", $params);
        }

        $params = ["cargaInicial" => $CargaInicial,
            "form" => $form->createView()];
        return $this->render("comun/cargaInicial/add.dependencia.html.twig", $params);
    }

    /**
     * @param Request $request
     * @param $cargaInicial_id
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Exception
     */
    public function queryDependenciaAction(Request $request, $cargaInicial_id) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(DependenciaDatatable::class);
        $datatable->buildDatatable();

        $CargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->find($cargaInicial_id);

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('cargaInicialDep = :cargaInicialDep');
            $qb->setParameter('cargaInicialDep', $CargaInicial);

            return $responseService->getResponse();
        }

        $params = ['cargaInicial' => $CargaInicial,
            'datatable' => $datatable];
        return $this->render('comun/cargaInicial/query.dependencia.html.twig', $params);
    }

    /**
     * @return Response
     */
    public function queryAction() {
        $CargaInicialAll = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->findAll();

        $params =['CargaInicialAll' => $CargaInicialAll];
        return $this->render('comun/cargaInicial/query.html.twig', $params);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteDependenciaAction($id) {
        $Dependencia = $this->getDoctrine()->getManager()->getRepository("ComunBundle:Dependencia")->find($id);
        $cargaInicial_id = $Dependencia->getCargaInicialDep()->getId();
        $status = 'Dependencia: ' . $Dependencia->getCargaInicial()->getTabla() . ' Eliminada Correctamente';
        $this->getDoctrine()->getManager()->remove($Dependencia);
        $this->getDoctrine()->getManager()->flush();

        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryDependencia", ["cargaInicial_id" => $cargaInicial_id]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function limpiaAction($id) {
        $em = $this->getDoctrine()->getManager();
        $CargaInicial_repo = $em->getRepository("ComunBundle:CargaInicial");
        $CargaInicial = $CargaInicial_repo->find($id);
        $EstadoCargaInicial = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $CargaInicial->setEstadoCargaInicial($EstadoCargaInicial);
        $CargaInicial->setFechaCarga(null);
        $CargaInicial->setFicheroLog(null);
        $em->persist($CargaInicial);
        $em->flush();

        $status = 'Carga Inicial:' . $CargaInicial->getTabla() . ' Inicializada Correctamente';
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryCargaInicial");
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $CargaInicial = new CargaInicial();

        $form = $this->createForm(CargaInicialType::class, $CargaInicial);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($CargaInicial);
            $em->flush();
            $status = 'Carga Inicial:' . $CargaInicial->getTabla() . ' Incluida Correctamente';
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("queryCargaInicial");
        }

        $params = ["CargaInicial" => $CargaInicial,
            "accion" => "CREACIÓN",
            "form" => $form->createView()];
        return $this->render("comun/cargaInicial/edit.html.twig", $params);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function lanzaAction($id) {
        $CargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->find($id);

        if ($this->verDependencias($CargaInicial)) {
            if ($CargaInicial->getProceso() != 'manual') {
                $root = $this->get('kernel')->getRootDir();
                $modo = $this->getParameter('modo');
                $php = $this->getParameter('php');
                $php_script = $php." " . $root . '/scripts/' . $CargaInicial->getProceso() . ' ' . $modo;
                exec($php_script, $SALIDA, $resultado);

                $ficheroLog = $CargaInicial->getTabla() . '.log';
                $ServicioLog = $this->get('app.escribelog');
                $ServicioLog->setLogger($CargaInicial->getTabla());
                foreach ($SALIDA as $linea) {
                    $ServicioLog->setMensaje($linea);
                    $ServicioLog->escribeLog($ficheroLog);
                }
                $CargaInicial->setFicheroLog($ServicioLog->getFilename());
                $fecha = new \DateTime();
                $EstadoCargaInicial = $resultado == 0 ? $this->getDoctrine()->getManager()->getRepository("ComunBundle:EstadoCargaInicial")->find(2) : $this->getDoctrine()->getManager()->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
                $CargaInicial->setEstadoCargaInicial($EstadoCargaInicial);
                $CargaInicial->setFechaCarga($fecha);
                $this->getDoctrine()->getManager()->persist($CargaInicial);
                $this->getDoctrine()->getManager()->flush();
                $params = ["CargaInicial" => $CargaInicial, "resultado" => $resultado];

                return $this->render("comun/cargaInicial/finProceso.html.twig", $params);
            } else {
                $fecha = new \DateTime();
                $EstadoCargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
                $CargaInicial->setEstadoCargaInicial($EstadoCargaInicial);
                $CargaInicial->setFechaCarga($fecha);
                $this->getDoctrine()->getManager()->persist($CargaInicial);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute("queryCargaInicial");
            }
        }
        $params = ["CargaInicial" => $CargaInicial];
        return $this->render("comun/cargaInicial/error.html.twig", $params);
    }

    /**
     * @param $id
     * @return Response
     */
    public function descargaLogAction($id) {

        $CargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->find($id);

        $filename = $CargaInicial->getFicherolog();

        $response = new Response();
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=1');
        $response->setContent(file_get_contents($filename));

        return $response;
    }

    /**
     * @param $CargaInicial
     * @return bool
     */
    function verDependencias($CargaInicial) {
        $Dependencias_repo = $this->getDoctrine()->getManager()->getRepository("ComunBundle:Dependencia");
        $DependenciasAll = $Dependencias_repo->createQueryBuilder('u')
                        ->where('u.cargaInicialDep = :cargaInicialDep')
                        ->setParameter(':cargaInicialDep', $CargaInicial)
                        ->getQuery()->getResult();
        $tablas = "";
        if ($DependenciasAll) {
            foreach ($DependenciasAll as $Dependencia) {
                if ($Dependencia->getCargaInicial()->getEstadoCargaInicial()->getId() != 2) { /* Estado pendiente de carga */
                    $tablas = $tablas . $Dependencia->getCargaInicial()->getTabla() . ', ';
                }
            }
            if ($tablas != "") {
                $status = '**ERROR TABLAS :' . $tablas . ' Pendiente de carga';
                $this->sesion->getFlashBag()->add("status", $status);
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}
