<?php

namespace CostesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CostesBundle\Form\ImportarType;
use Symfony\Component\HttpFoundation\Session\Session;
use CostesBundle\Entity\Ceco;
use CostesBundle\Form\CecoType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;

class CecoController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\CostesBundle\Datatables\CecoDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('costes/ceco/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verCecoAction($ceco_id) {
        $em = $this->getDoctrine()->getManager();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);

        $params = array("ceco" => $Ceco);
        return $this->render("costes/ceco/verCeco.html.twig", $params);
    }

    public function deleteAction($ceco_id) {
        $em = $this->getDoctrine()->getManager();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);

        $params = array("id" => $Ceco->getId(),
            "actuacion" => "DELETE");
        return $this->redirectToRoute("sincroCeco", $params);
    }

    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

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
                $em->persist($Ceco);
                $em->flush();
                $params = array("id" => $Ceco->getId(),
                    "actuacion" => "INSERT");
                return $this->redirectToRoute("sincroCeco", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UN CECO CON ESTE CÓDIGO: " . $Ceco->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }
        $params = ["form" => $CecoForm->createView(),
            "ceco" => $Ceco,
            "accion" => "CREACIÓN"];
        return $this->render("costes/ceco/edit.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
        $Ceco = $Ceco_repo->find($id);

        $CecoForm = $this->createForm(CecoType::class, $Ceco);
        $CecoForm->handleRequest($request);

        if ($CecoForm->isSubmitted()) {
            try {
                $em->persist($Ceco);
                $em->flush();
                $params = array("id" => $Ceco->getId(),
                    "actuacion" => "UPDATE");
                return $this->redirectToRoute("sincroCeco", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }
        $params = ["form" => $CecoForm->createView(),
            "ceco" => $Ceco,
            "accion" => "MODIFICACION"];
        return $this->render("costes/ceco/edit.html.twig", $params);
    }

    public function importaAction(Request $request) {
        $ImportarForm = $this->createForm(ImportarType::class);
        $ImportarForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($ImportarForm->isSubmitted()) {
            $file = $ImportarForm["fichero"]->getData();
            if (!empty($file) && $file != null) {
                $file_name = $file->getClientOriginalName();
                $file->move("upload", $file_name);
                $PHPExcel = $this->validarFichero($file);
                if ($PHPExcel == null) {
                    $status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
                    $this->sesion->getFlashBag()->add("status", $status);
                    $params = ["form" => $ImportarForm->createView()];
                    return $this->render("costes/ceco/importar.html.twig", $params);
                }

                $CargaFichero = new \ComunBundle\Entity\CargaFichero();
                $fecha = new \DateTime();
                $CargaFichero->setFechaCarga($fecha);
                $CargaFichero->setDescripcion("CARGA  MASIVA DE CENTROS DE COSTE");
                $CargaFichero->setFichero($file_name);
                $CargaFichero->setTabla("CCAP_CECO");
                $usuario_id = $this->sesion->get('usuario_id');
                $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
                $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
                $CargaFichero->setUsuario($Usuario);
                $CargaFichero->setEstadoCargaInicial($Estado);
                $em->persist($CargaFichero);
                $em->flush();

                $CargaFichero = $this->cargaCECO($CargaFichero, $PHPExcel);
               
                $em->persist($CargaFichero);
                $em->flush();

                $params = array("CargaFichero" => $CargaFichero,
                    "resultado" => 0);
                return $this->render("finCarga.html.twig", $params);
            }
        }

        $params = ["form" => $ImportarForm->createView()];
        return $this->render("costes/ceco/importar.html.twig", $params);
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

    public function cargaCECO($CargaFichero, $PHPExcel) {
        $em = $this->getDoctrine()->getManager();

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $Resultadocarga = array();
        $col = 0;

        $ficheroLog = 'cargaFichero-' . $CargaFichero->getId() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('FICHERO: ' . $ficheroLog);
        $ServicioLog->setMensaje("==> COMIENZA TRATAMIENTO PARA EL FICHERO: ");
        $ServicioLog->escribeLog($ficheroLog);
        $error = 0;
        for ($i = 2; $i <= $highestRow; $i++) {
            $em = $this->getDoctrine()->getManager();
            if (!$em->isOpen()) {
                $em = $this->getDoctrine()->getManager()->create($em->getConnection(), $em->getConfiguration());
            }
            $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':E' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $sociedad = $headingsArray["A"];
            $division = $headingsArray["B"];
            $codigo = $headingsArray["C"];
            $descripcion = $headingsArray["D"];
            $actuacion = $headingsArray["E"];

            $existe = $Ceco_repo->findCecoByCodigo($codigo);
            if ($existe) {
                $ServicioLog->setMensaje("**ERROR YA EXISTE CECO CON ES CODIGO: " . $codigo);
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }

            $Ceco = new Ceco();
            $Ceco->setSociedad($sociedad);
            $Ceco->setDivision($division);
            $Ceco->setCodigo($codigo);
            $Ceco->setDescripcion($descripcion);
            $em->persist($Ceco);
            $em->flush();
            $ServicioLog->setMensaje("=> CREADO CECO id: " . $Ceco->getId() . " Código: " . $Ceco->getCodigo() . " Descripción: " . $Ceco->getDescripcion());
            $ServicioLog->escribeLog($ficheroLog);

            $root = $this->get('kernel')->getRootDir();
            $modo = $this->getParameter('modo');
            $php_script = "php " . $root . "/scripts/costes/actualizacionCeco.php " . $modo . "  " . $Ceco->getId() . " INSERT ";

            $mensaje = exec($php_script, $SALIDA, $resultado);
            if ($resultado != 0)
                $error = 1;
            $ServicioLog->setLogger('ccap_ceco->codigo:' . $Ceco->getCodigo());
            foreach ($SALIDA as $linea) {
                $ServicioLog->setMensaje($linea);
                $ServicioLog->escribeLog($ficheroLog);
            }
            $ServicioLog->setLogger('FICHERO: ' . $ficheroLog);
            $ServicioLog->setMensaje("==>TERMINA TRATAMIENTO PARA EL FICHERO: ");
            $ServicioLog->escribeLog($ficheroLog);
        }
        if ($error == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
            $ServicioLog->setMensaje("==>TERMINA CORRECTAMENTE");
            $ServicioLog->escribeLog($ficheroLog);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
            $ServicioLog->setMensaje("==>TERMINA EN ERROR");
            $ServicioLog->escribeLog($ficheroLog);
        }

        $CargaFichero->setFicheroLog($ServicioLog->getFilename());
        $CargaFichero->setEstadoCargaInicial($Estado);

        return $CargaFichero;
    }

    public function sincroAction($id, $actuacion) {
        $em = $this->getDoctrine()->getManager();
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
        $Ceco = $em->getRepository("CostesBundle:Ceco")->find($id);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("ccap_cecos");
        $SincroLog->setIdElemento($id);
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Ceco->setSincroLog($SincroLog);
        $em->persist($Ceco);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/costes/actualizacionCeco.php " . $modo . "  " . $Ceco->getId() . " " . $actuacion;

        $mensaje = exec($php_script, $SALIDA, $resultado);
        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroCeco-' . $Ceco->getCodigo() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ccap_ceco->codigo:' . $Ceco->getCodigo());
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
        $view = $this->renderView("finSincro.html.twig", $params);

        $response = new Response($view);

        $response->headers->set('Content-Disposition', 'inline');
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('target', '_blank');

        return $response;
    }

//    public function verCiasAction($ceco_id) {
//        $em = $this->getDoctrine()->getManager();
//        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
//        $Ceco = $Ceco_repo->find($ceco_id);
//        $CecoCias_repo = $em->getRepository("CostesBundle:CecoCias");
//        $form = $this->createForm(\CostesBundle\Form\BuscaPlazaType::class);
//
//        $CecoCiasALL = $CecoCias_repo->createQueryBuilder('u')
//                        ->where('u.ceco = :ceco')
//                        ->setParameter('ceco', $Ceco)
//                        ->orderBy('u.id', 'desc')
//                        ->getQuery()->getResult();
//
//        $params = array("form" => $form->createView(),
//            "CecoCiasAll" => $CecoCiasALL);
//        return $this->render("costes/cecocias/query.html.twig", $params);
//    }

    public function ajaxVerCecoAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
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

    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Ceco = $em->getRepository("CostesBundle:Ceco")->find($id);
        $params = array("id" => $Ceco->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

}
