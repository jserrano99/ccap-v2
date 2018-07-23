<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class PlazaController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();
        $datatable = $this->get('sg_datatables.factory')->create(\AppBundle\Datatables\PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('plaza/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verPlazasByCecoAction(Request $request, $ceco_id) {

        $isAjax = $request->isXmlHttpRequest();
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);
        $datatable = $this->get('sg_datatables.factory')->create(\AppBundle\Datatables\PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('ceco = :ceco');
            $qb->setParameter('ceco', $Ceco);

            return $responseService->getResponse();
        }

        return $this->render('plaza/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verPlazasSinCecoAction(Request $request) {

        $isAjax = $request->isXmlHttpRequest();
        $entityManager = $this->getDoctrine()->getManager();
        $datatable = $this->get('sg_datatables.factory')->create(\AppBundle\Datatables\PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere("ceco.codigo is null");
            return $responseService->getResponse();
        }

        return $this->render('plaza/plazaSinCeco.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verPlazaAction($plaza_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Plaza = $Plaza_repo->find($plaza_id);
        $params = array("plaza" => $Plaza);
        return $this->render("plaza/verPlaza.html.twig", $params);
    }

    public function verPlazaSinCecoAction() {
        $entityManager = $this->getDoctrine()->getManager();
        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Plazas = $Plaza_repo->createQueryBuilder('u')
                        ->where("u.ceco is null and u.amortizada != 'S' ")
                        ->getQuery()->getResult();

        $params = array("plazaAll" => $Plazas);
        return $this->render("plaza/plazaSinCeco.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");

        $Plaza = $Plaza_repo->find($id);

        $form = $this->createForm(\AppBundle\Form\PlazaType::class, $Plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                if ($Plaza->getFAmortiza() == null) {
                    $Plaza->setAmortizada('N');
                } else {
                    $Plaza->setAmortizada('S');
                }
                if ($Plaza->getCeco() == null) {
                    if ($form->get('cecoInf')->getData() != null) {
                        $Ceco = $Ceco_repo->findCecoByCodigo($form->get('cecoInf')->getData());
                        $Plaza->setCeco($Ceco);
                    }
                }

                $entityManager->persist($Plaza);
                $entityManager->flush();
                $params = array("id" => $Plaza->getId(),
                    "actuacion" => "UPDATE");
                return $this->redirectToRoute("replicaPlaza", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA PLAZA CON ESTE CIAS : " . $Plaza->getCias();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPlaza");
            }
        }

        $params = array("form" => $form->createView(),
            "plaza" => $Plaza,
            "accion" => "MODIFICACIÓN");
        return $this->render("plaza/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");

        $Plaza = new \AppBundle\Entity\Plaza();

        $form = $this->createForm(\AppBundle\Form\PlazaType::class, $Plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                if ($Plaza->getFAmortiza() == null) {
                    $Plaza->setAmortizada('N');
                } else {
                    $Plaza->setAmortizada('S');
                }
                if ($form->get('cecoInf')->getData() != null) {
                    $Ceco = $Ceco_repo->findCecoByCodigo($form->get('cecoInf')->getData());
                    $Plaza->setCeco($Ceco);
                }
                $entityManager->persist($Plaza);
                $entityManager->flush();

                $params = array("id" => $Plaza->getId(),
                    "actuacion" => "INSERT");
                return $this->redirectToRoute("replicaPlaza", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA PLAZA CON ESTE CIAS : " . $Plaza->getCias();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPlaza");
            }
        }

        $params = array("form" => $form->createView(),
            "plaza" => $Plaza,
            "accion" => "NUEVA");
        return $this->render("plaza/edit.html.twig", $params);
    }

    public function replicaAction($id, $actuacion) {

        $entityManager = $this->getDoctrine()->getManager();
        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Plaza = $Plaza_repo->find($id);

        $resultado = $this->replicaPlaza($Plaza, $actuacion);
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("plaza/finProceso.html.twig", $params);
    }

    public function replicaPlaza($Plaza, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/actualizacionPlaza.php " . $modo . " " . $Plaza->getId() . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionPlaza.php " . $modo . " " . $Plaza->getId() . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;

        return $resultado;
    }

    public function calcularCiasAction($uf_id, $pa_id, $catgen_id) {

        $entityManager = $this->getDoctrine()->getManager();
        $Uf_repo = $entityManager->getRepository("AppBundle:Uf");
        $Uf = $Uf_repo->find($uf_id);
        $gerencia = substr($Uf->getOficial(), 2, 2); // posición 5-6 del Código Oficial

        $Pa_repo = $entityManager->getRepository("AppBundle:Pa");
        $Pa = $Pa_repo->find($pa_id);
        $zonaBasica = substr($Uf->getOficial(), 4, 2); // posición 5-6 del Código Oficial

        $CatGen_repo = $entityManager->getRepository("AppBundle:CatGen");
        $CatGen = $CatGen_repo->find($catgen_id);
        $tipoPuesto = $CatGen->getCodigo();

        $patron = '16' . $gerencia . $zonaBasica . $tipoPuesto;
        //dump($patron);

        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Plaza = $Plaza_repo->createQueryBuilder('u')
                        ->select('max(u.orden) as orden ')
                        ->where("u.cias like :patron ")
                        ->setParameter('patron', $patron . '%')
                        ->getQuery()->getResult();
        //dump($Plaza);
        $ultimoOrden = $Plaza[0]["orden"];
        if ($ultimoOrden == 99)
            $codigo["orden"] = 'XX';
        else
            $codigo["orden"] = sprintf('%02d', $ultimoOrden + 1);

        $codigo["cias"] = $patron . $codigo["orden"];

        $response = new Response();
        $response->setContent(json_encode($codigo));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

    public function ajaxCalcularCecoAction($cias, $uf_id, $pa_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Uf_repo = $entityManager->getRepository("AppBundle:Uf");
        $Uf = $Uf_repo->find($uf_id);
        $Pa_repo = $entityManager->getRepository("AppBundle:Pa");
        $Pa = $Pa_repo->find($pa_id);

        $CalculaCeco = $this->get('app.calculaCeco');
        $CalculaCeco->setUf($Uf);
        $CalculaCeco->setPa($Pa);
        $CalculaCeco->setCias($cias);

        $codigo = $CalculaCeco->calculaCeco();
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");

        $Ceco = $Ceco_repo->createQueryBuilder('u')
                        ->where('u.codigo = :codigo')
                        ->setParameter('codigo', $codigo)
                        ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        ;
        if ($Ceco) {
            $Ceco = $Ceco[0];
        } else {
            $Ceco["codigo"] = $codigo;
            $Ceco["descripcion"] = "ERROR NO EXISTE CECO";
        }
        $response = new Response();
        $response->setContent(json_encode($Ceco));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

    public function importarCecoAction(Request $request) {
        $ImportarForm = $this->createForm(\AppBundle\Form\ImportarType::class);
        $ImportarForm->handleRequest($request);

        if ($ImportarForm->isSubmitted()) {
            $file = $ImportarForm["fichero"]->getData();
            if (!empty($file) && $file != null) {
                $file_name = $file->getClientOriginalName();
                $file->move("upload", $file_name);
                $PHPExcel = $this->validarFichero($file);
                if ($PHPExcel != null) {
                    $resultado = $this->asignarCeco($PHPExcel);
                    $col = 0;
                    foreach ($resultado as $row) {
                        if ($row["estado"] == 'CORRECTO') {
                            $replica = $this->replicaAsignacion($row["id"], $row["accion"]);
                            $resultado[$col]["replicaLog"] = $replica["log"];
                        } else {
                            $resultado[$col]["replicaLog"][0] = "NO SE EJECUTA LA REPLICA EN BASE DE DATOS";
                        }
                        $col++;
                    }
                    $params = ["resultado" => $resultado];
                    return $this->render("plaza/finCarga.html.twig", $params);
                }
            }
        }

        $params = ["form" => $ImportarForm->createView()];
        return $this->render("plaza/importar.html.twig", $params);
    }

    public function validarFichero($fichero) {
        $Cabecera = array("A" => "CIAS",
            "B" => "CECO",
            "C" => "ACTUACION");

        $file = "upload/" . $fichero->getClientOriginalName();
        $PHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $headingsArray = $objWorksheet->rangeToArray('A1:C1', null, true, true, true);

        if ($headingsArray[1] != $Cabecera) {
            $status = " ERROR EN FORMATO FICHERO ";
            $this->sesion->getFlashBag()->add("status", $status);
            return null;
        }

        return $PHPExcel;
    }

    public function asignarCeco($PHPExcel) {
        $entityManager = $this->getDoctrine()->getManager();
        $Plaza_repo = $entityManager->getRepository("AppBundle:Plaza");
        $Ceco_repo = $entityManager->getRepository("AppBundle:Ceco");
        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $col = 0;
        for ($i = 2; $i <= $highestRow; $i++) {
            $entityManager = $this->getDoctrine()->getManager();
            if (!$entityManager->isOpen()) {
                $entityManager = $this->getDoctrine()->getManager()->create($entityManager->getConnection(), $entityManager->getConfiguration());
            }
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':E' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $cias = $headingsArray["A"];
            $ceco = $headingsArray["B"];
            $actuacion = $headingsArray["C"];
            $Plaza = $Plaza_repo->findPlazaByCias($cias);
            $Ceco = $Ceco_repo->findCecoByCodigo($ceco);
            $Resultadocarga[$col]["id"] = $Plaza->getId();

            if ($Plaza == null) {
                $Resultadocarga[$col]["cias"] = $cias;
                $Resultadocarga[$col]["ceco"] = $ceco;
                $Resultadocarga[$col]["estado"] = 'ERROR';
                $Resultadocarga[$col]["error"] = 'NO EXISTE LA PLAZA';
                $Resultadocarga[$col]["accion"] = '';
                continue;
            }
            if ($Ceco == null) {
                $Resultadocarga[$col]["cias"] = $cias;
                $Resultadocarga[$col]["ceco"] = $ceco;
                $Resultadocarga[$col]["estado"] = 'ERROR';
                $Resultadocarga[$col]["error"] = 'NO EXISTE CECO ';
                $Resultadocarga[$col]["accion"] = '';
                continue;
            }

            switch ($actuacion) {
                case "INSERT":
                    try {
                        $Plaza->setCeco($Ceco);
                        $entityManager->persist($Plaza);
                        $entityManager->flush();
                        $Resultadocarga[$col]["cias"] = $cias;
                        $Resultadocarga[$col]["ceco"] = $ceco;
                        $Resultadocarga[$col]["estado"] = 'CORRECTO';
                        $Resultadocarga[$col]["error"] = null;
                        $Resultadocarga[$col]["accion"] = 'INSERT';
                    } catch (Doctrine\DBAL\DBALException $ex) {
                        $Resultadocarga[$col]["cias"] = $cias;
                        $Resultadocarga[$col]["ceco"] = $ceco;
                        $Resultadocarga[$col]["estado"] = 'ERROR';
                        $Resultadocarga[$col]["error"] = "ERROR GENERAL=" . $ex->getMessage() . " CÓDIGO =" . $Ceco->getCodigo();
                        $Resultadocarga[$col]["accion"] = 'INSERT';
                    }
                    break;
                case "DELETE":
                    try {
                        $Plaza->setCeco(null);
                        $entityManager->persist($Plaza);
                        $entityManager->flush();
                        $Resultadocarga[$col]["cias"] = $cias;
                        $Resultadocarga[$col]["ceco"] = $ceco;
                        $Resultadocarga[$col]["estado"] = 'CORRECTO';
                        $Resultadocarga[$col]["error"] = null;
                        $Resultadocarga[$col]["accion"] = 'DELETE';
                    } catch (Doctrine\DBAL\DBALException $ex) {
                        $Resultadocarga[$col]["cias"] = $cias;
                        $Resultadocarga[$col]["ceco"] = $ceco;
                        $Resultadocarga[$col]["estado"] = 'ERROR';
                        $Resultadocarga[$col]["error"] = "ERROR GENERAL=" . $ex->getMessage() . " CÓDIGO =" . $Ceco->getCodigo();
                        $Resultadocarga[$col]["accion"] = 'DELETE';
                    }
                    break;
            }
            $col++;
        }
        return $Resultadocarga;
    }

    public function replicaAsignacion($id, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/actualizacionCecoCias.php 2 " . $id . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionCecoCias.php 1 " . $id . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;

        return $resultado;
    }

}
