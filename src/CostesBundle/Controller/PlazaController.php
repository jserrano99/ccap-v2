<?php

namespace CostesBundle\Controller;

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
        $datatable = $this->get('sg_datatables.factory')->create(\CostesBundle\Datatables\PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('costes/plaza/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verPlazasByCecoAction(Request $request, $ceco_id) {

        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);
        $datatable = $this->get('sg_datatables.factory')->create(\CostesBundle\Datatables\PlazaDatatable::class);
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

        return $this->render('costes/plaza/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verPlazasSinCecoAction(Request $request) {

        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $datatable = $this->get('sg_datatables.factory')->create(\CostesBundle\Datatables\PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere("ceco.codigo is null");
            return $responseService->getResponse();
        }

        return $this->render('costes/plaza/plazaSinCeco.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verPlazaAction($plaza_id) {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Plaza = $Plaza_repo->find($plaza_id);
        $params = array("plaza" => $Plaza);
        return $this->render("costes/plaza/verPlaza.html.twig", $params);
    }

    public function verPlazaSinCecoAction() {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Plazas = $Plaza_repo->createQueryBuilder('u')
                        ->where("u.ceco is null and u.amortizada != 'S' ")
                        ->getQuery()->getResult();

        $params = array("plazaAll" => $Plazas);
        return $this->render("costes/plaza/plazaSinCeco.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");

        $Plaza = $Plaza_repo->find($id);

        $form = $this->createForm(\CostesBundle\Form\PlazaType::class, $Plaza);
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

                $em->persist($Plaza);
                $em->flush();
                $params = array("id" => $Plaza->getId(),
                    "actuacion" => "UPDATE");
                return $this->redirectToRoute("sincroPlaza", $params);
                ;
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
        return $this->render("costes/plaza/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");

        $Plaza = new \CostesBundle\Entity\Plaza();

        $form = $this->createForm(\CostesBundle\Form\PlazaType::class, $Plaza);
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
                $em->persist($Plaza);
                $em->flush();

                $params = array("id" => $Plaza->getId(),
                    "actuacion" => "INSERT");
                return $this->redirectToRoute("sincroPlaza", $params);
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
        return $this->render("costes/plaza/edit.html.twig", $params);
    }

    public function calcularCiasAction($uf_id, $pa_id, $catgen_id) {

        $em = $this->getDoctrine()->getManager();
        $Uf_repo = $em->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($uf_id);
        $gerencia = substr($Uf->getOficial(), 2, 2); // posición 5-6 del Código Oficial

        $Pa_repo = $em->getRepository("CostesBundle:Pa");
        $Pa = $Pa_repo->find($pa_id);
        $zonaBasica = substr($Uf->getOficial(), 4, 2); // posición 5-6 del Código Oficial

        $CatGen_repo = $em->getRepository("MaestrosBundle:CatGen");
        $CatGen = $CatGen_repo->find($catgen_id);
        $tipoPuesto = $CatGen->getCodigo();

        $patron = '16' . $gerencia . $zonaBasica . $tipoPuesto;
        //dump($patron);

        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
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
        $em = $this->getDoctrine()->getManager();
        $Uf_repo = $em->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($uf_id);
        $Pa_repo = $em->getRepository("CostesBundle:Pa");
        $Pa = $Pa_repo->find($pa_id);

        $CalculaCeco = $this->get('app.calculaCeco');
        $CalculaCeco->setUf($Uf);
        $CalculaCeco->setPa($Pa);
        $CalculaCeco->setCias($cias);

        $codigo = $CalculaCeco->calculaCeco();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");

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
        $em = $this->getDoctrine()->getManager();

        $ImportarForm = $this->createForm(\CostesBundle\Form\ImportarType::class);
        $ImportarForm->handleRequest($request);

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
                    return $this->render("costes/plaza/importar.html.twig", $params);
                }

                $CargaFichero = new \ComunBundle\Entity\CargaFichero();
                $fecha = new \DateTime();
                $CargaFichero->setFechaCarga($fecha);
                $CargaFichero->setDescripcion("ASIGNACIÓN MASIVA DE CENTROS DE COSTE A PLAZAS");
                $CargaFichero->setFichero($file_name);
                $CargaFichero->setTabla("CCAP_PLAZAS(CECO)");
                $usuario_id = $this->sesion->get('usuario_id');
                $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
                $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
                $CargaFichero->setUsuario($Usuario);
                $CargaFichero->setEstadoCargaInicial($Estado);
                $em->persist($CargaFichero);
                $em->flush();

                return $this->asignarCeco($CargaFichero, $PHPExcel);
            }
        }

        $params = ["form" => $ImportarForm->createView()];
        return $this->render("costes/plaza/importar.html.twig", $params);
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
            return null;
        }

        return $PHPExcel;
    }

    public function asignarCeco($CargaFichero, $PHPExcel) {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $col = 0;

        $ficheroLog = 'cargaFichero-' . $CargaFichero->getId() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('FICHERO: '.$ficheroLog);
        $ServicioLog->setMensaje("==> COMIENZA TRATAMIENTO PARA EL FICHERO: ");
        $ServicioLog->escribeLog($ficheroLog);
                
        $error = 0;
        for ($i = 2; $i <= $highestRow; $i++) {
            $em = $this->getDoctrine()->getManager();
            if (!$em->isOpen()) {
                $em = $this->getDoctrine()->getManager()->create($em->getConnection(), $em->getConfiguration());
            }
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':E' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $cias = $headingsArray["A"];
            $ceco = $headingsArray["B"];
            $actuacion = $headingsArray["C"];
            $Plaza = $Plaza_repo->findPlazaByCias($cias);
            $Ceco = $Ceco_repo->findCecoByCodigo($ceco);

            $ServicioLog->setLogger('=>CIAS:' . $cias . " => CECO:". $ceco);
            if ($Plaza == null) {
                $ServicioLog->setMensaje("**ERROR NO EXISTE PLAZA PARA EL CIAS: ".$cias);
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }
            if ($Ceco == null) {
                $ServicioLog->setMensaje("**ERROR NO EXISTE CECO : ".$ceco);
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }

            switch ($actuacion) {
                case "INSERT":
                    $Plaza->setCeco($Ceco);
                    break;
                case "DELETE":
                    $Plaza->setCeco(null);
                    break;
            }
            try {
                $em->persist($Plaza);
                $em->flush();
                $ServicioLog->setMensaje('ASIGNADO CECO: ' . $ceco . " A PLAZA CIAS: " . $Plaza->getCias());
                $ServicioLog->escribeLog($ficheroLog);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $ServicioLog->setMensaje('**ERROR GENERAL CECO:' . $ceco . "CIAS:" . $cias . " ERROR: " . $ex->getmessage());
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }

            $resultado = $this->replicaAsignacion($Plaza->getId(), $actuacion);
            foreach ($resultado["salida"] as $linea) {
                $ServicioLog->setMensaje($linea);
                $ServicioLog->escribeLog($ficheroLog);
            }
            if ($resultado["resultado"] != 0)
                $error = 1;
        }
        $ServicioLog->setLogger('FICHERO: '.$ficheroLog);
        $ServicioLog->setMensaje("==> TERMINA TRATAMIENTO PARA EL FICHERO: ");
        $ServicioLog->escribeLog($ficheroLog);
                
        

        if ($error == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
            $ServicioLog->setMensaje("==> TERMINA CORRECTAMENTE");
            $ServicioLog->escribeLog($ficheroLog);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
            $ServicioLog->setMensaje("==> TERMINA EN ERROR");
            $ServicioLog->escribeLog($ficheroLog);
        }

        $CargaFichero->setFicheroLog($ServicioLog->getFilename());
        $CargaFichero->setEstadoCargaInicial($Estado);
        $em->persist($CargaFichero);
        $em->flush();

        $params = array("CargaFichero" => $CargaFichero,
            "resultado" => $error);
        return $this->render("finCarga.html.twig", $params);
    }

    public function replicaAsignacion($id, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/costes/actualizacionCecoCias.php " . $modo . " " . $id . " " . $actuacion;

        $mensaje = exec($php_script, $salida, $valor);
        
        $resultado["resultado"] = $valor;
        $resultado["salida"] = $salida;
        
        return $resultado;
    }

    public function sincroAction($id, $actuacion) {
        $em = $this->getDoctrine()->getManager();
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
        $Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("ccap_plaza");
        $SincroLog->setIdElemento($id);
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Plaza->setSincroLog($SincroLog);
        $em->persist($Plaza);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/costes/actualizacionPlaza.php " . $modo . "  " . $Plaza->getId() . " " . $actuacion;

        $mensaje = exec($php_script, $SALIDA, $resultado);
        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroPlaza-' . $Plaza->getCias() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ccap_plaza->cias:' . $Plaza->getCias());
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

    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);
        $params = array("id" => $Plaza->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

}
