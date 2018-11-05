<?php

namespace CostesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use CostesBundle\Entity\CecoCias;
use CostesBundle\Datatables\PlazaDatatable;
use CostesBundle\Datatables\CecoCiasDatatable;
use CostesBundle\Form\PlazaType;
use Doctrine\DBAL\DBALException;
use CostesBundle\Entity\Plaza;


class PlazaController extends Controller
{

    private $sesion;

    public function __construct()
    {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $datatable = $this->get('sg_datatables.factory')->create(PlazaDatatable::class);
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

    public function verPlazasByCecoAction(Request $request, $ceco_id)
    {

        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
        $Ceco = $Ceco_repo->find($ceco_id);
        $datatable = $this->get('sg_datatables.factory')->create(PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('cecoActual = :ceco');
            $qb->setParameter('ceco', $Ceco);

            return $responseService->getResponse();
        }

        return $this->render('costes/plaza/query.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    public function verPlazasSinCecoAction(Request $request)
    {

        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $datatable = $this->get('sg_datatables.factory')->create(PlazaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere("cecoActual.codigo is null");
            return $responseService->getResponse();
        }

        return $this->render('costes/plaza/plazaSinCeco.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    public function verPlazaAction($plaza_id)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Plaza = $Plaza_repo->find($plaza_id);
        $params = array("plaza" => $Plaza);
        return $this->render("costes/plaza/verPlaza.html.twig", $params);
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);

        $isAjax = $request->isXmlHttpRequest();
        $datatable = $this->get('sg_datatables.factory')->create(CecoCiasDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('plaza = :plaza');
            $qb->setParameter('plaza', $Plaza);

            return $responseService->getResponse();
        }

        $form = $this->createForm(PlazaType::class, $Plaza);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            try {
                if ($Plaza->getFAmortiza() == null) {
                    $Plaza->setAmortizada('N');
                } else {
                    $Plaza->setAmortizada('S');
                }

                $em->persist($Plaza);
                $em->flush();
                $params = array("id" => $Plaza->getId(),
                    "actuacion" => "UPDATE");
                return $this->redirectToRoute("sincroPlaza", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA PLAZA CON ESTE CIAS : " . $Plaza->getCias();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPlaza");
            }
        }

        $params = array("form" => $form->createView(),
            "plaza" => $Plaza,
            "accion" => "MODIFICACIÓN",
            "datatable" => $datatable);
        return $this->render("costes/plaza/edit.html.twig", $params);
    }

    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza = new Plaza();

        $form = $this->createForm(\CostesBundle\Form\PlazaType::class, $Plaza);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($Plaza->getFAmortiza() == null) {
                $Plaza->setAmortizada('N');
            } else {
                $Plaza->setAmortizada('S');
            }

            try {
                $em->persist($Plaza);
                $em->flush();

                $params = array("id" => $Plaza->getId(),
                    "actuacion" => "INSERT");
                return $this->redirectToRoute("sincroPlaza", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA PLAZA CON ESTE CIAS : " . $Plaza->getCias();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPlaza");
            }
        }

        $params = array("form" => $form->createView(),
            "plaza" => $Plaza,
            "accion" => "NUEVA",
            "datatable" => null);
        return $this->render("costes/plaza/edit.html.twig", $params);
    }

    public function calcularCiasAction($uf_id, $pa_id, $catgen_id)
    {

        $em = $this->getDoctrine()->getManager();
        $Uf_repo = $em->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($uf_id);
        $gerencia = substr($Uf->getOficial(), 2, 2); // posición 5-6 del Código Oficial

        $Pa_repo = $em->getRepository("CostesBundle:Pa");
        $Pa = $Pa_repo->find($pa_id);
        $zonaBasica = substr($Uf->getOficial(), 4, 2); // posición 5-6 del Cóextdigo Oficial

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

    public function ajaxCalcularCecoAction($cias, $uf_id, $pa_id)
    {
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
            ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);;
        if ($Ceco) {
            $Ceco = $Ceco[0];
        } else {
            $Ceco["id"] = null;
            $Ceco["codigo"] = $codigo;
            $Ceco["descripcion"] = "ERROR NO EXISTE CECO";
        }
        $response = new Response();
        $response->setContent(json_encode($Ceco));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function importarCecoAction(Request $request)
    {
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

    public function validarFichero($fichero)
    {
        $Cabecera = array("A" => "CECO",
            "B" => "CIAS",
            "C" => "FECHA INICIO");

        $file = "upload/" . $fichero->getClientOriginalName();
        $PHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $headingsArray = $objWorksheet->rangeToArray('A1:C1', null, true, true, true);

        if ($headingsArray[1] != $Cabecera) {
            return null;
        }

        return $PHPExcel;
    }

    public function validarFicheroAmort($fichero)
    {
        $Cabecera = array("A" => "EDIFICIO",
            "B" => "CIAS",
            "C" => "OBSERVACIONES",
            "D" => "FECHA");

        $file = "upload/" . $fichero->getClientOriginalName();
        $PHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $headingsArray = $objWorksheet->rangeToArray('A1:D1', null, true, true, true);

        if ($headingsArray[1] != $Cabecera) {
            dump($Cabecera);
            dump($headingsArray[1]);
            die();
            return null;
        }

        return $PHPExcel;
    }

    public function asignarCeco($CargaFichero, $PHPExcel)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");
        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
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
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':E' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $ceco = $headingsArray["A"];
            $cias = $headingsArray["B"];
            $fInicio = $headingsArray["C"];
            $Plaza = $Plaza_repo->findPlazaByCias($cias);
            $Ceco = $Ceco_repo->findCecoByCodigo($ceco);

            $ServicioLog->setLogger('=>CIAS:' . $cias . " => CECO:" . $ceco);
            if ($Plaza == null) {
                $ServicioLog->setMensaje("**ERROR NO EXISTE PLAZA PARA EL CIAS: " . $cias);
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }
            if ($Ceco == null) {
                $ServicioLog->setMensaje("**ERROR NO EXISTE CECO : " . $ceco);
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }
            $fechaInicio = new \DateTime($fInicio);
            $fechaFin = $fechaInicio > sub(new \DateInterval('P1D'));

            if ($Plaza->getCecoActual() != null) {
                $ServicioLog->setMensaje("CERRADO CECOCIAS CIAS= (" . $Plaza->getCias() . ") CECO =(" . $Plaza->getCecoActual()->getCodigo() . ") FECHA= (" . $fechaFin . ")");
                $ServicioLog->escribeLog($ficheroLog);
                $CecoCias = $this->selectCecocias($Plaza);
                $CecoCias->setFFin($fechaFin);
                $em->persist($CecoCias);
                $em->flush();
            }

            $CecoCias = new CecoCias();
            $CecoCias->setPlaza($Plaza);
            $CecoCias->setCeco($Ceco);
            $CecoCias->setFInicio($fechaInicio);
            $ServicioLog->setMensaje("CREADO CECOCIAS CIAS= (" . $Plaza->getCias() . ") CECO =(" . $Ceco->getCodigo() . ") FECHA= (" . $fechaInicio . ")");
            $ServicioLog->escribeLog($ficheroLog);
            $em->persist($CecoCias);
            $em->flush();

            $ServicioLog->setMensaje('ASIGNADO CECO ACTUAL : ' . $Ceco->getCodigo() . " A PLAZA CIAS: " . $Plaza->getCias());
            $ServicioLog->escribeLog($ficheroLog);
            $Plaza->setCecoActual($Ceco);
            $em->persist($Plaza);
            $em->flush();

            /**
             * SINCRONIZACIÓN CON SAINT-6
             */
//            $resultado = $this->sincroCecoCias($CecoCias->getId());

            foreach ($resultado as $linea) {
                $ServicioLog->setMensaje($linea);
                $ServicioLog->escribeLog($ficheroLog);
            }
        }
        $ServicioLog->setLogger('FICHERO: ' . $ficheroLog);
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

    public function sincroAction($id, $actuacion)
    {
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
        $php = $this->getParameter('php');
        $php_script = $php . " " . $root . "/scripts/costes/actualizacionPlaza.php " . $modo . "  " . $Plaza->getId() . " " . $actuacion;

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

    public function descargaLogAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);
        $params = array("id" => $Plaza->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

    public function exportarAction($datatable)
    {

        $em = $this->getDoctrine()->getManager();
        $PlazaAll = $em->getRepository("CostesBundle:Plaza")->findAll();

        $PHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $PHPExcel->setActiveSheetIndex(0);
        $sheet->setCellValueByColumnAndRow(1, 1, 'Total Registros :' . count($PlazaAll));
        $row = 3;
        $sheet->setCellValueByColumnAndRow(1, $row, 'CIAS');
        $sheet->setCellValueByColumnAndRow(2, $row, 'CÓDIGO UNIDAD FUNCIONAL');
        $sheet->setCellValueByColumnAndRow(3, $row, 'DESCRIPCIÓN UNIDAD FUNCIONAL');
        $sheet->setCellValueByColumnAndRow(4, $row, 'CÓDIGO PUNTO ASISTENCIAL');
        $sheet->setCellValueByColumnAndRow(5, $row, 'DESCRIPCIÓN PUNTO ASISTENCIAL');
        $sheet->setCellValueByColumnAndRow(6, $row, 'CODIGO CATEGORIA GENERAL');
        $sheet->setCellValueByColumnAndRow(7, $row, 'DESCRIPCIÓN CATEGORIA GENERAL');
        $sheet->setCellValueByColumnAndRow(8, $row, 'CENTRO DE COSTE');
        $sheet->setCellValueByColumnAndRow(9, $row, 'FECHA AMORTIZACIÓN');

        $row++;
        foreach ($PlazaAll as $Plaza) {
            $sheet->setCellValueByColumnAndRow(1, $row, $Plaza->getCias());
            $sheet->setCellValueByColumnAndRow(2, $row, $Plaza->getUf()->getUf());
            $sheet->setCellValueByColumnAndRow(3, $row, $Plaza->getUf()->getDescripcion());
            $sheet->setCellValueByColumnAndRow(4, $row, $Plaza->getPa()->getPa());
            $sheet->setCellValueByColumnAndRow(5, $row, $Plaza->getPa()->getDescripcion());
            $sheet->setCellValueByColumnAndRow(6, $row, $Plaza->getCatGen()->getCodigo());
            $sheet->setCellValueByColumnAndRow(7, $row, $Plaza->getCatGen()->getDescripcion());
            if ($Plaza->getCeco()) {
                $sheet->setCellValueByColumnAndRow(8, $row, $Plaza->getCeco()->getCodigo());
            } else {
                $sheet->setCellValueByColumnAndRow(8, $row, '');
            }
            $sheet->setCellValueByColumnAndRow(9, $row, $Plaza->getFAmortiza());
            $row++;
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($PHPExcel);
        $filename = 'PLAZAS.xlsx';
        $writer->save($filename);

        $response = new Response();
        $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=1');
        $response->setContent(file_get_contents($filename));

        return $response;
    }

    public function verCecoCiasAction(Request $request, $plaza_id)
    {
        $isAjax = $request->isXmlHttpRequest();
        $datatable = $this->get('sg_datatables.factory')->create(\CostesBundle\Datatables\CecoCiasDatatable::class);
        $datatable->buildDatatable();
        $Plaza = $this->getDoctrine()->getManager()->getRepository("CostesBundle:Plaza")->find($plaza_id);

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('plaza = :plaza');
            $qb->setParameter('plaza', $Plaza);

            return $responseService->getResponse();
        }

        return $this->render('costes/cecocias/query.html.twig', array(
            'datatable' => $datatable,
            'plaza' => $Plaza
        ));
    }

    public function cambiarAsignacionAction($cias, $nuevoCeco, $fInicio, $cecoAnterior_id)
    {

        $fecha = new \DateTime($fInicio);
        $fecha->sub(new \DateInterval('P1D'));
        /** restamos un día */
        $fechaInicio = new \DateTime($fInicio);
        $em = $this->getDoctrine()->getManager();
        $PlazaAll = $em->getRepository("CostesBundle:Plaza")->createQueryBuilder('u')
            ->where("u.cias = :cias")
            ->setParameter('cias', $cias)
            ->getQuery()->getResult();
        $Plaza = $PlazaAll[0];
        $NuevoCecoAll = $em->getRepository("CostesBundle:Ceco")->createQueryBuilder('u')
            ->where("u.codigo = :codigo")
            ->setParameter('codigo', $nuevoCeco)
            ->getQuery()->getResult();
        $NuevoCeco = $NuevoCecoAll[0];


        if ($cecoAnterior_id != 0) {
            $CecoAnterior = $em->getRepository("CostesBundle:Ceco")->find($cecoAnterior_id);
            $CecoCias_repo = $em->getRepository("CostesBundle:CecoCias");
            $CecoCiasAntAll = $CecoCias_repo->createQueryBuilder('u')
                ->where("u.plaza = :plaza and u.ceco = :ceco")
                ->setParameter('plaza', $Plaza)
                ->setParameter('ceco', $CecoAnterior)
                ->getQuery()->getResult();

            $CecoCiasAnt = $CecoCiasAntAll[0];
            $CecoCiasAnt->setFFin($fecha);
            $em->persist($CecoCiasAnt);
            $em->flush();
        }
        $CecoCiasNew = new CecoCias();
        $CecoCiasNew->setPlaza($Plaza);
        $CecoCiasNew->setCeco($NuevoCeco);
        $CecoCiasNew->setFInicio($fechaInicio);
        $em->persist($CecoCiasNew);
        $em->flush();

        $Plaza->setCecoActual($NuevoCeco);
        $em->persist($Plaza);
        $em->flush();

        $params = array('id' => $CecoCiasNew->getId());
        return $this->redirectToRoute("sincroCecoCias", $params);


        //return $this->redirectToRoute("verCecoCias", array("plaza_id" => $Plaza->getId()));
    }

    public function selectCecoCias($Plaza)
    {
        $em = $this->getDoctrine()->getManager();
        $CecoCeciasAll = $em->getRepository("CostesBundle:CecoCias")->createQueryBuilder('u')
            ->where('u.plaza = :plaza and u.ceco = :ceco')
            ->addWhere('u.fFin is null')
            ->setParameter('ceco', $Plaza->getCecoActual())
            ->setParameter('plaza', $Plaza)
            ->getQuery->getResult();
        return ($CecoCeciasAll[0]);
    }

    public function sincroCecoCiasAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $CecoCias = $em->getRepository("CostesBundle:CecoCias")->find($id);
        $Plaza = $CecoCias->getPlaza();

        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("ccap_plaza");
        $SincroLog->setIdElemento($id);
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);


        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php = $this->getParameter('php');
        $php_script = $php . " " . $root . "/scripts/costes/actualizacionCecoCias.php " . $modo . "  " . $id;

        $mensaje = exec($php_script, $SALIDA, $resultado);
        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroCecoCias-' . $CecoCias->getPlaza()->getCias() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ccap_plaza->Ceco:' . $CecoCias->getCeco()->getCodigo() . 'Cias: ' . $CecoCias->getPlaza()->getCias() . " Fecha :" . $CecoCias->getFInicio()->format('d/m/Y'));
        foreach ($SALIDA as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog($ficheroLog);
        }
        $SincroLog->setScript($php_script);
        $SincroLog->setFicheroLog($ServicioLog->getFilename());
        $SincroLog->setEstado($Estado);

        $em->persist($SincroLog);
        $em->flush();

        $Plaza->setSincroLog($SincroLog);
        $em->persist($Plaza);
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

    public function amortizacionPlazaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);

        $form = $this->createForm(\CostesBundle\Form\AmortizacionPlazaType::class, $Plaza);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $ok = $this->compruebaAmortizacion($Plaza);
            if ($ok == 0) {
                try {
                    $Plaza->setAmortizada('S');
                    $em->persist($Plaza);
                    $em->flush();
                    $params = array("id" => $Plaza->getId(),
                        "actuacion" => "UPDATE");
                    return $this->redirectToRoute("sincroPlaza", $params);
                } catch (Doctrine\DBAL\DBALException $ex) {
                    $status = "ERROR GENERAL=" . $ex->getMessage();
                    $this->sesion->getFlashBag()->add("status", $status);
                    return $this->redirectToRoute("queryPlaza");
                }
            } else {
                $Plaza->setFAmortiza(null);
            }
        }

        $params = array("form" => $form->createView(),
            "plaza" => $Plaza,
            "accion" => "AMORTIZACION");
        return $this->render("costes/plaza/amortiza.html.twig", $params);
    }

    public function desAmortizacionAction(Request $request, $cias)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza = $em->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);

        try {
            $Plaza->setFAmortiza(null);
            $Plaza->setAmortizada('N');
            $em->persist($Plaza);
            $em->flush();
            $params = ["id" => $Plaza->getId(),
                "actuacion" => "UPDATE"];
            return $this->redirectToRoute("sincroPlaza", $params);
        } catch (Doctrine\DBAL\DBALException $ex) {
            $status = "ERROR GENERAL=" . $ex->getMessage();
            $this->sesion->getFlashBag()->add("status", $status);
        }


    }

    public function compruebaAmortizacion($Plaza)
    {
        $em = $this->getDoctrine()->getManager();
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();


        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php = $this->getParameter('php');
        $php_script = $php . " " . $root . "/scripts/costes/compruebaAmortizacionPlaza.php " . $modo . " " . $Plaza->getCias() . " " . $Plaza->getFAmortiza()->format('Y-m-d');

        $mensaje = exec($php_script, $SALIDA, $resultado);
//        dump($mensaje);
//        dump($resultado);
//        dump($SALIDA);
//        die();
//        
        if ($resultado != 0) {
            $ficheroLog = 'compruebaAmortizacion-' . $Plaza->getCias() . '.log';
            $ServicioLog = $this->get('app.escribelog');
            $ServicioLog->setLogger('ccap_plaza->cias:' . $Plaza->getCias());
            foreach ($SALIDA as $linea) {
                $ServicioLog->setMensaje($linea);
                $ServicioLog->escribeLog($ficheroLog);
            }
            $SincroLog->setUsuario($Usuario);
            $SincroLog->setTabla("ccap_plaza");
            $SincroLog->setIdElemento($Plaza->getId());
            $SincroLog->setFechaProceso($fechaProceso);
            $SincroLog->setEstado($Estado);
            $em->persist($SincroLog);

            $SincroLog->setScript($php_script);
            $SincroLog->setFicheroLog($ServicioLog->getFilename());
            $SincroLog->setEstado($Estado);
            $em->persist($SincroLog);
            $em->flush();

            $Plaza->setSincroLog($SincroLog);
            $Plaza->setFAmortiza(null);
            $em->persist($Plaza);
            $em->flush();

            $linea = " ERROR EXISTEN ALTAS O PUESTOS ABIERTOS PARA ESTA FECHA DE AMORTIZACION VER LOG DE MODIFICACION ";
            $this->sesion->getFlashBag()->add("status", $linea);
        }

        return $resultado;
    }

    public function amortizacionMasivaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ImportarForm = $this->createForm(\CostesBundle\Form\ImportarType::class);
        $ImportarForm->handleRequest($request);

        if ($ImportarForm->isSubmitted()) {
            $file = $ImportarForm["fichero"]->getData();
            if (!empty($file) && $file != null) {
                $file_name = $file->getClientOriginalName();
                $file->move("upload", $file_name);
                $PHPExcel = $this->validarFicheroAmort($file);
                if ($PHPExcel == null) {
                    $status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
                    $this->sesion->getFlashBag()->add("status", $status);
                    $params = ["form" => $ImportarForm->createView()];
                    return $this->render("costes/plaza/amortizacion.masiva.html.twig", $params);
                }

            }
            $CargaFichero = new \ComunBundle\Entity\CargaFichero();
            $fecha = new \DateTime();
            $CargaFichero->setFechaCarga($fecha);
            $CargaFichero->setDescripcion("AMORTIZACIÓN MASIVA DE PLAZAS");
            $CargaFichero->setFichero($file_name);
            $CargaFichero->setTabla("CCAP_PLAZAS(AMORTIZACION)");
            $usuario_id = $this->sesion->get('usuario_id');
            $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
            $CargaFichero->setUsuario($Usuario);
            $CargaFichero->setEstadoCargaInicial($Estado);
            $em->persist($CargaFichero);
            $em->flush();

            return $this->amortizaPlazaFichero($CargaFichero, $PHPExcel);
        }


        $params = ["form" => $ImportarForm->createView()];
        return $this->render("costes/plaza/amortizacion.masiva.html.twig", $params);

    }

    public function amortizaPlazaFichero($CargaFichero, $PHPExcel)
    {
        $em = $this->getDoctrine()->getManager();
        $Plaza_repo = $em->getRepository("CostesBundle:Plaza");

        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $col = 0;

        $ficheroLog = 'amortizacionMasiva-' . $CargaFichero->getId() . '.log';
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
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':D' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];
            $cias = $headingsArray["B"];
            $observaciones = $headingsArray["C"];
            $fecha = $headingsArray["D"];
            $fechaAmortizacion = new \DateTime($fecha);

            $Plaza = $Plaza_repo->findPlazaByCias($cias);
            $ServicioLog->setLogger('TRATAMIENTO CIAS: ' . $cias);
            $ServicioLog->setMensaje('=> AMORTIZACIÓN CIAS: (' . $cias . ') OBSERVA: (' . $observaciones . ') FECHA: (' . $fechaAmortizacion->format('Y-m-d') . ')');
            $ServicioLog->escribeLog($ficheroLog);

            if ($Plaza == null) {
                $ServicioLog->setMensaje("**ERROR NO EXISTE PLAZA PARA EL CIAS: " . $cias);
                $ServicioLog->escribeLog($ficheroLog);
                $error = 1;
                continue;
            }
            $root = $this->get('kernel')->getRootDir();
            $modo = $this->getParameter('modo');
            $php = $this->getParameter('php');
            $php_script = $php . " " . $root . "/scripts/costes/compruebaAmortizacionPlaza.php " . $modo . " " . $cias . " " . $fechaAmortizacion->format('Y-m-d');
            $SALIDA = array();
            $mensaje = exec($php_script, $SALIDA, $resultado);
            foreach ($SALIDA as $linea) {
                $ServicioLog->setLogger('Comprueba Amortización CIAS: ' . $cias);
                $ServicioLog->setMensaje($linea);
                $ServicioLog->escribeLog($ficheroLog);
            }

            if ($resultado == 0) {
                $Plaza->setObservaciones($observaciones);
                $Plaza->setFAmortiza($fechaAmortizacion);
                $Plaza->setAmortizada("S");
                $em->persist($Plaza);
                $em->flush();

                $php_script = $php . " " . $root . "/scripts/costes/actualizacionPlaza.php " . $modo . "  " . $Plaza->getId() . " UPDATE";
                $SALIDA2 = array();
                $mensaje2 = exec($php_script, $SALIDA2, $resultado);
                foreach ($SALIDA2 as $linea2) {
                    $ServicioLog->setLogger('Actualización Plaza(CIAS): ' . $cias);
                    $ServicioLog->setMensaje($linea2);
                    $ServicioLog->escribeLog($ficheroLog);
                }


            } else {
                $ServicioLog->setMensaje(">>>>NO SE TRATA<<<<<");
                $ServicioLog->escribeLog($ficheroLog);
            }
        }

        $ServicioLog->setLogger('FICHERO: ' . $ficheroLog);
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
}
