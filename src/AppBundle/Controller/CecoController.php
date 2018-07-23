<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ImportarType;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Ceco;
use AppBundle\Form\CecoType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;

class CecoController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function indexAction(Request $request) {
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\AppBundle\Datatables\CecoDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('ceco/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verCecoAction($ceco_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);

        $params = array("ceco" => $Ceco);
        return $this->render("ceco/verCeco.html.twig", $params);
    }

    public function deleteAction($ceco_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);
        $resultado = $this->replicaCECOS($Ceco, "DELETE");
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];
        $entityManager->remove($Ceco);
        $entityManager->flush();

        return $this->render("ceco/finProceso.html.twig", $params);
    }

    public function addAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();

        $Ceco = new Ceco();
        $Ceco->setSociedad('1017');
        $Ceco->setDivision('D225');

        $CecoForm = $this->createForm(CecoType::class, $Ceco);
        $CecoForm->handleRequest($request);

        if ($CecoForm->isSubmitted()) {
            $Ceco = new Ceco();
            $Ceco->setSociedad($CecoForm->get('sociedad')->getData());
            $Ceco->setDivision($CecoForm->get('division')->getData());
            $Ceco->setCodigo($CecoForm->get('codigo')->getData());
            $Ceco->setDescripcion($CecoForm->get('descripcion')->getData());
            try {
                $EM->persist($Ceco);
                $EM->flush();
                $resultado = $this->replicaCECOS($Ceco, "INSERT");
                $params = ["error" => $resultado["error"],
                    "salida" => $resultado["log"]];
                return $this->render("ceco/finProceso.html.twig", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UN CECO CON ESTE CÓDIGO: " . $Ceco->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
//return $this->redirectToRoute("queryCeco");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
//return $this->redirectToRoute("queryCeco");
            }
        }
        $params = ["form" => $CecoForm->createView(), 
                    "ceco" => $Ceco ,
                    "accion" => "CREACIÓN"];
        return $this->render("ceco/edit.html.twig", $params);
    }

    public function editAction(Request $request, $id ) {
        $EM = $this->getDoctrine()->getManager();
        $Ceco_repo = $EM->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->find($id);
        
        $CecoForm = $this->createForm(CecoType::class, $Ceco);
        $CecoForm->handleRequest($request);

        if ($CecoForm->isSubmitted()) {
            try {
                $EM->persist($Ceco);
                $EM->flush();
                $resultado = $this->replicaCECOS($Ceco, "UPDATE");
                $params = ["error" => $resultado["error"],
                    "salida" => $resultado["log"]];
                return $this->render("ceco/finProceso.html.twig", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }
        $params = ["form" => $CecoForm->createView(),
                   "ceco" => $Ceco,
                   "accion" => "MODIFICACION"];
        return $this->render("ceco/edit.html.twig", $params);
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
                    $resultado = $this->cargaCECO($PHPExcel);
                    $col = 0;
                    foreach ($resultado as $row) {
                        if ($row["estado"] == 'CORRECTO') {
                            $Ceco = $row["ceco"];
                            $replica = $this->replicaCECOS($Ceco, $row["accion"]);
                            $resultado[$col]["replicaLog"] = $replica["log"];
                        } else {
                            $resultado[$col]["replicaLog"][0] = "NO SE EJECUTA LA REPLICA EN BASE DE DATOS DE SAINT6";
                        }
                        $col++;
                    }
                    $params = ["resultado" => $resultado];
                    return $this->render("ceco/finCarga.html.twig", $params);
                }
            }
        }

        $params = ["form" => $ImportarForm->createView()];
        return $this->render("ceco/importar.html.twig", $params);
    }

    public function validarFichero($fichero) {
        $Cabecera = array("A" => "SOCIEDAD",
            "B" => "DIVISION",
            "C" => "CECO",
            "D" => "DESCRIPCION",
            "E" => "ACTUACION");

        $file = "upload/" . $fichero->getClientOriginalName();
        $PHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $headingsArray = $objWorksheet->rangeToArray('A1:E1', null, true, true, true);
        $linea = $headingsArray[1];

        if ($linea != $Cabecera) {
            $status = " ERROR EN FORMATO FICHERO ";
            $this->sesion->getFlashBag()->add("status", $status);
            return null;
        }

        return $PHPExcel;
    }

    public function cargaCECO($PHPExcel) {
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
            $Ceco_repo = $EM->getRepository("AppBundle:Ceco");
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':E' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $sociedad = $headingsArray["A"];
            $division = $headingsArray["B"];
            $codigo = $headingsArray["C"];
            $descripcion = $headingsArray["D"];
            $actuacion = $headingsArray["E"];
            switch ($actuacion) {
                case "INSERT":
                    $Ceco = new Ceco();
                    $Ceco->setSociedad($sociedad);
                    $Ceco->setDivision($division);
                    $Ceco->setCodigo($codigo);
                    $Ceco->setDescripcion($descripcion);
                    try {
                        $EM->persist($Ceco);
                        $EM->flush();
                        $Resultadocarga[$col]["ceco"] = $Ceco;
                        $Resultadocarga[$col]["estado"] = 'CORRECTO';
                        $Resultadocarga[$col]["error"] = null;
                        $Resultadocarga[$col]["accion"] = 'INSERT';
                    } catch (UniqueConstraintViolationException $ex) {
                        $Resultadocarga[$col]["ceco"] = $Ceco;
                        $Resultadocarga[$col]["estado"] = 'ERROR';
                        $Resultadocarga[$col]["error"] = " YA EXISTE UN CECO CON ESTE CÓDIGO: " . $Ceco->getCodigo();
                        $Resultadocarga[$col]["accion"] = 'INSERT';
                    } catch (Doctrine\DBAL\DBALException $ex) {
                        $Resultadocarga[$col]["ceco"] = $Ceco;
                        $Resultadocarga[$col]["estado"] = 'ERROR';
                        ;
                        $Resultadocarga[$col]["error"] = "ERROR GENERAL=" . $ex->getMessage() . " CÓDIGO =" . $Ceco->getCodigo();
                        $Resultadocarga[$col]["accion"] = 'INSERT';
                    }
                    break;
                case "DELETE":
                    $Ceco = $Ceco_repo->findCecoByCodigo($codigo);
                    if ($Ceco) {
                        try {
                            $EM->remove($Ceco);
                            $EM->flush();
                            $Resultadocarga[$col]["ceco"] = $Ceco;
                            $Resultadocarga[$col]["estado"] = 'CORRECTO';
                            $Resultadocarga[$col]["error"] = null;
                            $Resultadocarga[$col]["accion"] = 'DELETE';
                        } catch (ForeignKeyConstraintViolationException $ex) {
                            $Resultadocarga[$col]["ceco"] = $Ceco;
                            $Resultadocarga[$col]["estado"] = 'ERROR';
                            $Resultadocarga[$col]["error"] = " EXISTE PLAZAS ASIGNADAD A ESTE CECO: " . $Ceco->getCodigo();
                            $Resultadocarga[$col]["accion"] = 'DELETE';
                        } catch (Doctrine\DBAL\DBALException $ex) {
                            $Resultadocarga[$col]["ceco"] = $Ceco;
                            $Resultadocarga[$col]["estado"] = 'ERROR';
                            $Resultadocarga[$col]["error"] = "ERROR GENERAL=" . $ex->getMessage() . " CÓDIGO =" . $Ceco->getCodigo();
                            $Resultadocarga[$col]["accion"] = 'DELETE';
                        }
                    }
                    break;
            }
            $col++;
        }
        return $Resultadocarga;
    }

    public function replicaCecoAction($ceco_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);

        $resultado = $this->replicaCECOS($Ceco, "INSERT");
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("ceco/finProceso.html.twig", $params);
    }

    public function replicaCECOS($Ceco, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/actualizacionCeco.php  " . $modo . " " . $Ceco->getId() . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionCeco.php " . $modo . "  " . $Ceco->getId() . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;

        return $resultado;
    }

    public function verCiasAction($ceco_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);
        $CecoCias_repo = $entityManager->getRepository("AppBundle:CecoCias");
        $form = $this->createForm(\AppBundle\Form\BuscaPlazaType::class);

        $CecoCiasALL = $CecoCias_repo->createQueryBuilder('u')
                        ->where('u.ceco = :ceco')
                        ->setParameter('ceco', $Ceco)
                        ->orderBy('u.id', 'desc')
                        ->getQuery()->getResult();

        $params = array("form" => $form->createView(),
            "CecoCiasAll" => $CecoCiasALL);
        return $this->render("cecocias/query.html.twig", $params);
    }

    public function ajaxVerCecoAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->createQueryBuilder('u')
                        ->where('u.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery()->getResult(Query::HYDRATE_ARRAY);
        ;
        $Ceco = $Ceco[0];
        $response = new Response();
        $response->setContent(json_encode($Ceco));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

}
