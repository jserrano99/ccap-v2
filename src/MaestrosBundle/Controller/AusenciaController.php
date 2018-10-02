<?php

namespace MaestrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AusenciaController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\AusenciaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/ausencia/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function queryEqAusenciaAction(Request $request, $ausencia_id) {
        $em = $this->getDoctrine()->getManager();
        $Ausencia_repo = $em->getRepository("MaestrosBundle:Ausencia");
        $Ausencia = $Ausencia_repo->find($ausencia_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqAusenciaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('ausencia = :ausencia');
            $qb->setParameter('ausencia', $Ausencia);

            return $responseService->getResponse();
        }

        return $this->render('maestros/ausencia/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    } 

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ausencia_repo = $entityManager->getRepository("MaestrosBundle:Ausencia");
        $Ausencia = $Ausencia_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\AusenciaType::class, $Ausencia);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($Ausencia);
            $entityManager->flush();
            $params = array("id" => $Ausencia->getId(),
                "actuacion" => "UPDATE",
                "eqausencia_id" => "TT");
            return $this->redirectToRoute("sincroAusencia", $params);
        }

        $params = array("Ausencia" => $Ausencia,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/ausencia/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ausencia_repo = $entityManager->getRepository("MaestrosBundle:Ausencia");
        $Ausencia = new \MaestrosBundle\Entity\Ausencia();

        $form = $this->createForm(\MaestrosBundle\Form\AusenciaType::class, $Ausencia);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Ausencia);
                $entityManager->flush();
                $this->creaEquivalencias($Ausencia);
                $params = array("id" => $Ausencia->getId(),
                    "actuacion" => "INSERT",
                    "eqausencia_id" => "NULL");
                return $this->redirectToRoute("sincroAusencia", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = "Error ya existe una ausencia con este codigo: " . $Ausencia->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("Ausencia" => $Ausencia,
            "form" => $form->createView(),
            "accion" => 'CREACIÓN');
        return $this->render("maestros/ausencia/edit.html.twig", $params);
    }

    public function creaEquivalencias($Ausencia) {

        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                        ->where("u.area = 'S' ")
                        ->getQuery()->getResult();
        foreach ($EdificioAll as $Edificio) {
            $EqAusencia = new \MaestrosBundle\Entity\EqAusencia();
            $EqAusencia->setEdificio($Edificio);
            $EqAusencia->setAusencia($Ausencia);
            $EqAusencia->setEnuso('S');
            $EqAusencia->setCodigoLoc($Ausencia->getCodigo());
            $entityManager->persist($EqAusencia);
            $entityManager->flush();
        }

        return true;
    }

    public function exportaAction() {

        $entityManager = $this->getDoctrine()->getManager();
        $Ausencia_repo = $entityManager->getRepository("MaestrosBundle:Ausencia");
        $AusenciaAll = $Ausencia_repo->findAll();

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Hello world');

        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save('demo.xlsx');
        $params = array();
        $response = new \Symfony\Component\HttpFoundation\Response();
        $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'demo.xlsx');

        $response->headers->set('Content-Type', 'application/vnd.excel');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->headers->set('Content-Disposition', 'attachment; filename=demo.xlsx');
        $response->setContent(file_get_contents("demo.xlsx"));

        return $response;
    }

    public function sincroAction($id, $actuacion, $eqausencia_id) {
        $em = $this->getDoctrine()->getManager();
        $Ausencia = $em->getRepository("MaestrosBundle:Ausencia")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("gums_ausencia");
        $SincroLog->setIdElemento($Ausencia->getId());
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Ausencia->setSincroLog($SincroLog);
        $em->persist($Ausencia);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/maestros/actualizacionAusencia.php " . $modo . " " . $Ausencia->getId() . " " . $actuacion . " " . $eqausencia_id;
        $mensaje = exec($php_script, $SALIDA, $resultado);

        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroAusencia-' . $Ausencia->getCodigo() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('gums_ausencia->codigo:' . $Ausencia->getCodigo());
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
        $CatFp = $em->getRepository("MaestrosBundle:Ausencia")->find($id);
        $params = array("id" => $Ausencia->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

    public function activarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqAusencia = $em->getRepository("MaestrosBundle:EqAusencia")->find($id);
        $params = array("id" => $EqAusencia->getAusencia()->getId(),
            "actuacion" => 'ACTIVAR',
            "edificio" => $EqAusencia->getEdificio()->getCodigo(),
            "eqausencia_id" => $EqAusencia->getId());
        return $this->redirectToRoute("sincroAusencia", $params);
    }

    public function desactivarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqAusencia = $em->getRepository("MaestrosBundle:EqAusencia")->find($id);
        $params = array("id" => $EqAusencia->getAusencia()->getId(),
            "actuacion" => 'DESACTIVAR',
            "edificio" => $EqAusencia->getEdificio()->getCodigo(),
            "eqausencia_id" => $EqAusencia->getId());
        return $this->redirectToRoute("sincroAusencia", $params);
    }

    public function crearAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqAusencia = $em->getRepository("MaestrosBundle:EqAusencia")->find($id);
        if ($EqAusencia->getCodigoLoc() == 'XXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = array("ausencia_id" => $EqAusencia->getAusencia()->getId());
            return $this->redirectToRoute("queryEqAusencia", $params);
        }
        $params = array("id" => $EqAusencia->getAusencia()->getId(),
            "actuacion" => 'CREAR',
            "eqausencia_id" => $EqAusencia->getId());
        return $this->redirectToRoute("sincroAusencia", $params);
    }

}
