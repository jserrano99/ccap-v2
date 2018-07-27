<?php

namespace CostesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CostesBundle\Form\ImportarType;
use CostesBundle\Entity\Excepcion;

class ExcepcionController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new \Symfony\Component\HttpFoundation\Session\Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable= $this->get('sg_datatables.factory')->create(\CostesBundle\Datatables\ExcepcionDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('costes/excepcion/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Excepcion = new \CostesBundle\Entity\Excepcion();
        $form = $this->createForm(\CostesBundle\Form\ExcepcionType::class, $Excepcion);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($this->actualiza($form)) {
                return $this->redirectToRoute("queryExcepcion");
            }
        }

        $params = array("accion" => "NUEVA",
            "form" => $form->createView());
        return $this->render("costes/excepcion/edit.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Excepcion_repo = $entityManager->getRepository("CostesBundle:Excepcion");
        $Excepcion = $Excepcion_repo->find($id);

        $form = $this->createForm(\CostesBundle\Form\ExcepcionType::class, $Excepcion);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($this->actualiza($form)) {
                return $this->redirectToRoute("queryExcepcion");
            }
        }
        $params = array("accion" => "MODIFICACION",
            "form" => $form->createView());
        return $this->render("costes/excepcion/edit.html.twig", $params);
    }

    public function actualiza($form) {
        $Excepcion = $form->getNormData();
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("CostesBundle:Ceco");
        if ($form->get('cecoRealInf')->getData() != null) {
            $Ceco = $Ceco_repo->findCecoByCodigo($form->get('cecoRealInf')->getData());
            if (!$Ceco) {
                $status = " Error no existe ceco real  : " . $form->get('cecoRealInf')->getData() . " no creado ";
                $this->sesion->getFlashBag()->add("status", $status);
                return false;
            } else {
                $Excepcion->setCecoReal($Ceco);
            }
        } else {
            $Excepcion->setCecoReal($form->get('cecoReal')->getData());
        }
        if ($form->get('cecoExcepcionInf')->getData() != null) {
            $Ceco = $Ceco_repo->findCecoByCodigo($form->get('cecoExcepcionInf')->getData());
            if (!$Ceco) {
                $status = " Error no existe ceco excepcion : " . $form->get('cecoExcepcionInf')->getData() . " no creado ";
                $this->sesion->getFlashBag()->add("status", $status);
                return false;
            } else {
                $Excepcion->setCecoExcepcion($Ceco);
            }
        } else {
            $Excepcion->setCecoReal($form->get('cecoExcepcion')->getData());
        }

        try {
            $entityManager->persist($Excepcion);
            $entityManager->flush();
            return true;
        } catch (\Doctrine\DBAL\DBALException $ex) {
            $status = " Error DBAL " . $ex->getMessage() . " no creado ";
            $this->sesion->getFlashBag()->add("status", $status);
            return false;
        }
    }

    public function importaAction(Request $request) {
        $ImportarForm = $this->createForm(ImportarType::class);
        $ImportarForm->handleRequest($request);

        if ($ImportarForm->isSubmitted()) {
            $file = $ImportarForm["fichero"]->getData();
            if (!empty($file) && $file != null) {
                $file_name = $file->getClientOriginalName();
                $file->move("upload", $file_name);
                $PHPExcel = $this->validarFichero($file);
                if ($PHPExcel != null) {
                    $resultado = $this->cargaExcepcion($PHPExcel);
                    $params = ["resultado" => $resultado];
                    return $this->render("costes/excepcion/finCarga.html.twig", $params);
                }
            }
        }

        $params = ["form" => $ImportarForm->createView()];
        return $this->render("costes/excepcion/importar.html.twig", $params);
    }

    public function validarFichero($fichero) {
        $Cabecera = array("A" => "DESCRIPCION",
            "B" => "CECO REAL",
            "C" => "CECO EXCEPCION"
        );

        $file = "upload/" . $fichero->getClientOriginalName();
        $PHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $headingsArray = $objWorksheet->rangeToArray('A1:C1', null, true, true, true);
        $linea = $headingsArray[1];

        if ($linea != $Cabecera) {
            $status = " ERROR EN FORMATO FICHERO ";
            $this->sesion->getFlashBag()->add("status", $status);
            return null;
        }

        return $PHPExcel;
    }

    public function cargaExcepcion($PHPExcel) {
        $EM = $this->getDoctrine()->getManager();

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $Resultadocarga = array();
        $col = 0;
        for ($i = 2; $i <= $highestRow; $i++) {
            $EM = $this->getDoctrine()->getManager();
            if (!$EM->isOpen()) {
                $EM = $this->getDoctrine()->getManager()->create($EM->getConnection(), $EM->getConfiguration());
            }
            $Ceco_repo = $EM->getRepository("CostesBundle:Ceco");
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':C' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $descripcion = $headingsArray["A"];
            $cecoReal = $headingsArray["B"];
            $cecoExcepcion = $headingsArray["C"];
            $Resultadocarga[$col]["cecoReal"] = $cecoReal;
            $Resultadocarga[$col]["cecoExcepcion"] = $cecoExcepcion;
            $CecoReal = $Ceco_repo->findCecoByCodigo($cecoReal);
            if (!$CecoReal) {
                $Resultadocarga[$col]["estado"] = 'ERROR';
                $Resultadocarga[$col]["error"] = ' NO EXISTE CENTRO DE COSTE REAL';
                continue;
            }

            $CecoExcepcion = $Ceco_repo->findCecoByCodigo($cecoExcepcion);
            if (!$CecoExcepcion) {
                $Resultadocarga[$col]["estado"] = 'ERROR';
                $Resultadocarga[$col]["error"] = ' NO EXISTE CENTRO DE COSTE EXCEPCION';
                continue;
            }

            $Excepcion = new Excepcion();
            $Excepcion->setCecoReal($CecoReal);
            $Excepcion->setCecoExcepcion($CecoExcepcion);
            $Excepcion->setDescripcion($descripcion);
            try {
                $EM->persist($Excepcion);
                $EM->flush();
                $Resultadocarga[$col]["estado"] = 'CORRECTO';
                $Resultadocarga[$col]["error"] = null;
            } catch (UniqueConstraintViolationException $ex) {
                $Resultadocarga[$col]["estado"] = 'ERROR';
                $Resultadocarga[$col]["error"] = " YA EXISTE UNA EXCEPCION PARA ESTE : " . $CecoReal->getCodigo();
            } catch (Doctrine\DBAL\DBALException $ex) {
                $Resultadocarga[$col]["estado"] = 'ERROR';
                $Resultadocarga[$col]["error"] = "ERROR GENERAL=" . $ex->getMessage() . " CÓDIGO =" . $CecoReal->getCodigo();
            }
            $col++;
        }
        return $Resultadocarga;
    }

}
