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

    public function verEquiAction(Request $request, $id) {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $Ausencia_repo = $em->getRepository("MaestrosBundle:Ausencia");
        $Ausencia = $Ausencia_repo->find($id);

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

        return $this->render('eqausencia/query.html.twig', array(
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
            $resultado = $this->sincroniza($Ausencia->getId(), "UPDATE");
            $params = array("error" => $resultado["error"],
                "log" => $resultado["log"]);
            return $this->render('finSincro.html.twig', $params);
        }

        $params = array("Ausencia" => $Ausencia,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/ausencia/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ausencia_repo = $entityManager->getRepository("MaestrosBundle:Ausencia");
        $Ausencia = $Ausencia_repo->find($id);
        $resultado = $this->sincroniza($Ausencia->getId(), "DELETE");
        $entityManager->remove($Ausencia);
        $entityManager->flush();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('finSincro.html.twig', $params);
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
                $this->creaEquivalencia($Ausencia);
                $resultado = $this->sincroniza($Ausencia->getId(), "INSERT");
                $params = array("error" => $resultado["error"],
                    "log" => $resultado["log"]);
                return $this->render('finSincro.html.twig', $params);
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
        return $this->render("ausencia/edit.html.twig", $params);
    }

    public function creaEquivalencia($Ausencia) {

        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("MaestrosBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                        ->where("u.area = 'S' ")
                        ->getQuery()->getResult();
        foreach ($EdificioAll as $Edificio) {
            $EqAusencia = new \MaestrosBundle\Entity\EqAusencia();
            $EqAusencia->setEdificio($Edificio);
            $EqAusencia->setAusencia($Ausencia);
            $EqAusencia->setCodigoLoc($Ausencia->getCodigo());
            $entityManager->persist($EqAusencia);
            $entityManager->flush();
        }

        return true;
    }

    public function sincroniza($id, $accion) {

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/sincroAusencia.php  " . $modo . " " . $id . " " . $accion;

        $mensaje = exec($php_script, $salida, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $salida;

        return $resultado;
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

}
