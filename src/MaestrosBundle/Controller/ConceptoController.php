<?php

namespace MaestrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ConceptoController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\ConceptoDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/concepto/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function queryEqConceptoAction(Request $request, $concepto_id) {
        $em = $this->getDoctrine()->getManager();
        $Concepto_repo = $em->getRepository("MaestrosBundle:Concepto");
        $Concepto = $Concepto_repo->find($concepto_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqConceptoDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('concepto = :concepto');
            $qb->setParameter('concepto', $Concepto);

            return $responseService->getResponse();
        }

        return $this->render('maestros/concepto/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    } 

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Concepto_repo = $entityManager->getRepository("MaestrosBundle:Concepto");
        $Concepto = $Concepto_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\ConceptoType::class, $Concepto);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($Concepto);
            $entityManager->flush();
            $params = array("id" => $Concepto->getId(),
                "actuacion" => "UPDATE",
                "eqconcepto_id" => "TT");
            return $this->redirectToRoute("sincroConcepto", $params);
        }

        $params = array("Concepto" => $Concepto,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/concepto/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Concepto_repo = $entityManager->getRepository("MaestrosBundle:Concepto");
        $Concepto = new \MaestrosBundle\Entity\Concepto();

        $form = $this->createForm(\MaestrosBundle\Form\ConceptoType::class, $Concepto);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Concepto);
                $entityManager->flush();
                $this->creaEquivalencias($Concepto);
                $params = array("id" => $Concepto->getId(),
                    "actuacion" => "INSERT",
                    "eqconcepto_id" => "NULL");
                return $this->redirectToRoute("sincroConcepto", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = "Error ya existe una concepto con este codigo: " . $Concepto->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("Concepto" => $Concepto,
            "form" => $form->createView(),
            "accion" => 'CREACIÓN');
        return $this->render("maestros/concepto/edit.html.twig", $params);
    }

    public function creaEquivalencias($Concepto) {

        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                        ->where("u.area = 'S' ")
                        ->getQuery()->getResult();
        foreach ($EdificioAll as $Edificio) {
            $EqConcepto = new \MaestrosBundle\Entity\EqConcepto();
            $EqConcepto->setEdificio($Edificio);
            $EqConcepto->setConcepto($Concepto);
            $EqConcepto->setEnuso('S');
            $EqConcepto->setCodigoLoc($Concepto->getCodigo());
            $entityManager->persist($EqConcepto);
            $entityManager->flush();
        }

        return true;
    }

    public function sincroAction($id, $actuacion, $eqconcepto_id) {
        $em = $this->getDoctrine()->getManager();
        $Concepto = $em->getRepository("MaestrosBundle:Concepto")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("gums_concepto");
        $SincroLog->setIdElemento($Concepto->getId());
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Concepto->setSincroLog($SincroLog);
        $em->persist($Concepto);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php = $this->getParameter('php');
        $php_script = $php." " . $root . "/scripts/maestros/actualizacionConcepto.php " . $modo . " " . $Concepto->getId() . " " . $actuacion . " " . $eqconcepto_id;
        $mensaje = exec($php_script, $SALIDA, $resultado);

        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroConcepto-' . $Concepto->getCodigo() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('gums_concepto->codigo:' . $Concepto->getCodigo());
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
        $Concepto = $em->getRepository("MaestrosBundle:Concepto")->find($id);
        $params = array("id" => $Concepto->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

    public function activarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqConcepto = $em->getRepository("MaestrosBundle:EqConcepto")->find($id);
        $params = array("id" => $EqConcepto->getConcepto()->getId(),
            "actuacion" => 'ACTIVAR',
            "edificio" => $EqConcepto->getEdificio()->getCodigo(),
            "eqconcepto_id" => $EqConcepto->getId());
        return $this->redirectToRoute("sincroConcepto", $params);
    }

    public function desactivarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqConcepto = $em->getRepository("MaestrosBundle:EqConcepto")->find($id);
        $params = array("id" => $EqConcepto->getConcepto()->getId(),
            "actuacion" => 'DESACTIVAR',
            "edificio" => $EqConcepto->getEdificio()->getCodigo(),
            "eqconcepto_id" => $EqConcepto->getId());
        return $this->redirectToRoute("sincroConcepto", $params);
    }

    public function crearAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqConcepto = $em->getRepository("MaestrosBundle:EqConcepto")->find($id);
        if ($EqConcepto->getCodigoLoc() == 'XXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = array("concepto_id" => $EqConcepto->getConcepto()->getId());
            return $this->redirectToRoute("queryEqConcepto", $params);
        }
        $params = array("id" => $EqConcepto->getConcepto()->getId(),
            "actuacion" => 'CREAR',
            "eqconcepto_id" => $EqConcepto->getId());
        return $this->redirectToRoute("sincroConcepto", $params);
    }

}
