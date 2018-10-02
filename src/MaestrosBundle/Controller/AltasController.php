<?php

namespace MaestrosBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;

class AltasController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\AltasDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/altas/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function queryEqAltasAction(Request $request, $altas_id) {
        $em = $this->getDoctrine()->getManager();
        $Altas_repo = $em->getRepository("MaestrosBundle:Altas");
        $Altas = $Altas_repo->find($altas_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqAltasDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('altas = :altas');
            $qb->setParameter('altas', $Altas);

            return $responseService->getResponse();
        }

        return $this->render('maestros/altas/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Altas_repo = $entityManager->getRepository("MaestrosBundle:Altas");
        $Altas = $Altas_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\AltasType::class, $Altas);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Altas);
                $entityManager->flush();
                $params = array("id" => $Altas->getId(),
                    "actuacion" => "UPDATE",
                    "eqaltas_id" => "TT");
                return $this->redirectToRoute("sincroAltas", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("AltasAltas");
            }
        }

        $params = array("Altas" => $Altas,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/altas/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Altas_repo = $entityManager->getRepository("MaestrosBundle:Altas");
        $Altas = new \MaestrosBundle\Entity\Altas();

        $form = $this->createForm(\MaestrosBundle\Form\AltasType::class, $Altas);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Altas);
                $entityManager->flush();
                $this->crearEquivalencias($Altas);
                $params = array("id" => $Altas->getId(),
                    "actuacion" => "INSERT",
                    "eqaltas_id" => "NULL");
                return $this->redirectToRoute("sincroAltas", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = "Error ya existe un motivo de alta este codigo: " . $Altas->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryAltas");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryAltas");
            }
        }

        $params = array("Altas" => $Altas,
            "form" => $form->createView(),
            "accion" => 'CREACIÓN');
        return $this->render("maestros/altas/edit.html.twig", $params);
    }

    public function crearEquivalencias($Altas) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->querySoloAreas();
        foreach ($EdificioAll as $Edificio) {
            $EqAltas = new \MaestrosBundle\Entity\EqAltas();
            $EqAltas->setAltas($Altas);
            $EqAltas->setEdificio($Edificio);
            $EqAltas->setCodigoLoc($Altas->getCodigo());
            $EqAltas->setEnuso('S');
            $entityManager->persist($EqAltas);
            $entityManager->flush();
        }
        return true;
    }

    public function sincroAction($id, $actuacion, $eqaltas_id) {
        $em = $this->getDoctrine()->getManager();
        $Altas = $em->getRepository("MaestrosBundle:Altas")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("gums_altas");
        $SincroLog->setIdElemento($Altas->getId());
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Altas->setSincroLog($SincroLog);
        $em->persist($Altas);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/maestros/actualizacionAltas.php " . $modo . " " . $Altas->getId() . " " . $actuacion . " " . $eqaltas_id;
        $mensaje = exec($php_script, $SALIDA, $resultado);

        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroAltas-' . $Altas->getCodigo() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('gums_altas->codigo:' . $Altas->getCodigo());
        foreach ($SALIDA as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog($ficheroLog);
        }
        $SincroLog->setScript($php_script);
        $SincroLog->setFicheroLog($ServicioLog->getFilename());
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);
        $em->flush();

        $params = array("SincroLog" => $SincroLog,
            "resultado" => $resultado);
        return $this->render("maestros/finSincro.html.twig", $params);
    }

    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $CatFp = $em->getRepository("MaestrosBundle:Altas")->find($id);
        $params = array("id" => $Altas->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

    public function activarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqAltas = $em->getRepository("MaestrosBundle:EqAltas")->find($id);
        $params = array("id" => $EqAltas->getAltas()->getId(),
            "actuacion" => 'ACTIVAR',
            "edificio" => $EqAltas->getEdificio()->getCodigo(),
            "eqaltas_id" => $EqAltas->getId());
        return $this->redirectToRoute("sincroAltas", $params);
    }

    public function desactivarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqAltas = $em->getRepository("MaestrosBundle:EqAltas")->find($id);
        $params = array("id" => $EqAltas->getAltas()->getId(),
            "actuacion" => 'DESACTIVAR',
            "edificio" => $EqAltas->getEdificio()->getCodigo(),
            "eqaltas_id" => $EqAltas->getId());
        return $this->redirectToRoute("sincroAltas", $params);
    }

    public function crearAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqAltas = $em->getRepository("MaestrosBundle:EqAltas")->find($id);
        if ($EqAltas->getCodigoLoc() == 'XXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = array("altas_id" => $EqAltas->getAltas()->getId());
            return $this->redirectToRoute("queryEqAltas", $params);
        }
        $params = array("id" => $EqAltas->getAltas()->getId(),
            "actuacion" => 'CREAR',
            "eqaltas_id" => $EqAltas->getId());
        return $this->redirectToRoute("sincroAltas", $params);
    }

}
