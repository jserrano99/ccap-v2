<?php

namespace ComunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;

class CargaInicialController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function addDependenciaAction(Request $request, $cargaInicial_id) {
        $CargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->find($cargaInicial_id);
        $Dependencia = new \ComunBundle\Entity\Dependencia();
        $Dependencia->setCargaInicialDep($CargaInicial);

        $form = $this->createForm(\ComunBundle\Form\DependenciaType::class, $Dependencia);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($Dependencia);
            $CargaInicial->setNumDep($CargaInicial->getNumDep() + 1);
            $this->getDoctrine()->getManager()->persist($CargaInicial);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("queryDependencia", array("cargaInicial_id" => $cargaInicial_id));
        }

        $params = array("cargaInicial" => $CargaInicial,
            "form" => $form->createView());
        return $this->render("comun/cargaInicial/add.dependencia.html.twig", $params);
    }

    public function queryDependenciaAction(Request $request, $cargaInicial_id) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\ComunBundle\Datatables\DependenciaDatatable::class);
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

        return $this->render('comun/cargaInicial/query.dependencia.html.twig', array(
                    'cargaInicial' => $CargaInicial,
                    'datatable' => $datatable,
        ));
    }

    public function queryAction() {
        $CargaInicialAll = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->findAll();

        return $this->render('comun/cargaInicial/query.html.twig', array(
                    'CargaInicialAll' => $CargaInicialAll,
        ));
    }

    public function deleteDependenciaAction($id) {
        $Dependencia = $this->getDoctrine()->getManager()->getRepository("ComunBundle:Dependencia")->find($id);
        $cargaInicial_id = $Dependencia->getCargaInicialDep()->getId();
        $status = 'Dependencia: ' . $Dependencia->getCargaInicial()->getTabla() . ' Eliminada Correctamente';

        $this->getDoctrine()->getManager()->remove($Dependencia);
        $this->getDoctrine()->getManager()->flush();

        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryDependencia", array("cargaInicial_id" => $cargaInicial_id));
    }

    public function limpiaAction($id) {
        $em = $this->getDoctrine()->getManager();
        $CargaInicial_repo = $em->getRepository("ComunBundle:CargaInicial");
        $CargaInicial = $CargaInicial_repo->find($id);
        $EstadoCargaInicial = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $db = $em->getConnection();
 
        $query = "delete from ".$CargaInicial->getTabla();
        $stmt = $db->prepare($query);
        $stmt->execute();

        $CargaInicial->setEstadoCargaInicial($EstadoCargaInicial);
        $CargaInicial->setFechaCarga(null);
        $CargaInicial->setFicheroLog(null);
        $em->persist($CargaInicial);
        $em->flush();

        $status = 'Carga Inicial:' . $CargaInicial->getTabla() . ' Inicializada Correctamente';
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryCargaInicial");
    }

    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $CargaInicial_repo = $em->getRepository("ComunBundle:CargaInicial");
        $CargaInicial = new \ComunBundle\Entity\CargaInicial();

        $form = $this->createForm(\ComunBundle\Form\CargaInicialType::class, $CargaInicial);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($CargaInicial);
            $em->flush();
            $status = 'Carga Inicial:' . $CargaInicial->getTabla() . ' Incluida Correctamente';
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("queryCargaInicial");
        }

        $params = array("CargaInicial" => $CargaInicial,
            "accion" => "CREACIÓN",
            "form" => $form->createView());
        return $this->render("comun/cargaInicial/edit.html.twig", $params);
    }

    public function lanzaAction($id) {
        $CargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaInicial")->find($id);

        if ($this->verDependencias($CargaInicial)) {
            if ($CargaInicial->getProceso() != 'manual') {
                $root = $this->get('kernel')->getRootDir();
                $modo = $this->getParameter('modo');
                $php_script = "php " . $root . '/scripts/' . $CargaInicial->getProceso() . ' ' . $modo;
                $mensaje = exec($php_script, $SALIDA, $resultado);

                $ficheroLog = $CargaInicial->getTabla() . '.log';
                $ServicioLog = $this->get('app.escribelog');
                $ServicioLog->setLogger($CargaInicial->getTabla());
                foreach ($SALIDA as $linea) {
                    $ServicioLog->setMensaje($linea);
                    $ServicioLog->escribeLog($ficheroLog);
                }
                $CargaInicial->setFicheroLog($ServicioLog->getFilename());
                $fecha = new \DateTime();
                if ($resultado == 0) {
                    $EstadoCargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
                } else {
                    $EstadoCargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
                }
                $CargaInicial->setEstadoCargaInicial($EstadoCargaInicial);
                $CargaInicial->setFechaCarga($fecha);
                $this->getDoctrine()->getManager()->persist($CargaInicial);
                $this->getDoctrine()->getManager()->flush();
                $params = array("CargaInicial" => $CargaInicial, "resultado" => $resultado);

                return $this->render("comun/cargaInicial/finProceso.html.twig", $params);
            } else {
                $resultado = 0;
                $fecha = new \DateTime();
                $EstadoCargaInicial = $this->getDoctrine()->getManager()->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
                $CargaInicial->setEstadoCargaInicial($EstadoCargaInicial);
                $CargaInicial->setFechaCarga($fecha);
                $this->getDoctrine()->getManager()->persist($CargaInicial);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute("queryCargaInicial");
            }
        }
        $params = array("CargaInicial" => $CargaInicial);
        return $this->render("comun/cargaInicial/error.html.twig", $params);
    }

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
