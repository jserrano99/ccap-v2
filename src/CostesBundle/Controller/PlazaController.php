<?php

namespace CostesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Query;
use CostesBundle\Entity\CecoCias;
use CostesBundle\Entity\Plaza;
use ComunBundle\Entity\CargaFichero;
use CostesBundle\Datatables\PlazaDatatable;
use CostesBundle\Datatables\CecoCiasDatatable;
use CostesBundle\Datatables\TempAltasDatatable;
use ComunBundle\Entity\SincroLog;
use CostesBundle\Form\AmortizacionPlazaType;
use CostesBundle\Form\ImportarType;
use CostesBundle\Form\PlazaType;
use DateTime;
use DateInterval;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class PlazaController
 * @package CostesBundle\Controller
 */
class PlazaController extends Controller
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
	private $sesion;
	/**
	 * PlazaController constructor.
	 */
	public function __construct()
	{
		$this->sesion = new Session();
	}
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
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
		$params = ['datatable' => $datatable];
		return $this->render('costes/plaza/query.html.twig', $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param $ceco_id
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
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
		$params = ['datatable' => $datatable];
		return $this->render('costes/plaza/query.html.twig', $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function verPlazasSinCecoAction(Request $request)
	{

		$isAjax = $request->isXmlHttpRequest();
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
		$params = ['datatable' => $datatable];
		return $this->render('costes/plaza/plazaSinCeco.html.twig', $params);
	}

	/**
	 * @param $plaza_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function verPlazaAction($plaza_id)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza_repo = $em->getRepository("CostesBundle:Plaza");
		$Plaza = $Plaza_repo->find($plaza_id);
		$params = ["plaza" => $Plaza];
		return $this->render("costes/plaza/verPlaza.html.twig", $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
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
				$params = ["id" => $Plaza->getId(),
					"actuacion" => "UPDATE",
					"cias" => $Plaza->getCias()];
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

		$params = ["form" => $form->createView(),
			"plaza" => $Plaza,
			"accion" => "MODIFICACIÓN",
			"datatable" => $datatable];
		return $this->render("costes/plaza/edit.html.twig", $params);
	}
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza = new Plaza();

		$form = $this->createForm(PlazaType::class, $Plaza);
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
				$params = ["id" => $Plaza->getId(),
					"actuacion" => "INSERT",
					"cias" => $Plaza->getCias()];
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

		$params = ["form" => $form->createView(),
			"plaza" => $Plaza,
			"accion" => "NUEVA",
			"datatable" => null];
		return $this->render("costes/plaza/edit.html.twig", $params);
	}
	/**
	 * @param $uf_id
	 * @param $catgen_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function calcularCiasAction($uf_id, $catgen_id)
	{

		$em = $this->getDoctrine()->getManager();
		$Uf_repo = $em->getRepository("CostesBundle:Uf");
		$Uf = $Uf_repo->find($uf_id);
		$gerencia = substr($Uf->getOficial(), 2, 2); // posición 5-6 del Código Oficial

		$zonaBasica = substr($Uf->getOficial(), 4, 2); // posición 5-6 del Cóextdigo Oficial

		$CatGen_repo = $em->getRepository("MaestrosBundle:CatGen");
		$CatGen = $CatGen_repo->find($catgen_id);
		$tipoPuesto = $CatGen->getCodigo();

		$patron = '16' . $gerencia . $zonaBasica . $tipoPuesto;

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
	/**
	 * @param $cias
	 * @param $uf_id
	 * @param $pa_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
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
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
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

		$ImportarForm = $this->createForm(ImportarType::class);
		$ImportarForm->handleRequest($request);

		if ($ImportarForm->isSubmitted()) {
			$file = $ImportarForm["fichero"]->getData();
			if (!empty($file) && $file != null) {
				$file_name = $file->getClientOriginalName();
				$file->move("upload", $file_name);
				try {
					$PHPExcel = $this->validarFichero($file);
					$CargaFichero = new CargaFichero();
					$fecha = new DateTime();
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
				} catch (Exception $ex) {
					$status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
					$this->sesion->getFlashBag()->add("status", $status);
					$params = ["form" => $ImportarForm->createView()];
					return $this->render("costes/plaza/importar.html.twig", $params);
				}
			}
		}

		$params = ["form" => $ImportarForm->createView()];
		return $this->render("costes/plaza/importar.html.twig", $params);
	}
	/**
	 * @param $fichero
	 * @return null|\PhpOffice\PhpSpreadsheet\Spreadsheet
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function validarFichero($fichero)
	{
		$Cabecera = ["A" => "CECO",
			"B" => "CIAS",
			"C" => "FECHA INICIO"];

		$file = "upload/" . $fichero->getClientOriginalName();
		$PHPExcel = IOFactory::load($file);
		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$headingsArray = $objWorksheet->rangeToArray('A1:C1', null, true, true, true);

		if ($headingsArray[1] != $Cabecera) {
			return null;
		}

		return $PHPExcel;
	}

	/**
	 * @param $fichero
	 * @return null|\PhpOffice\PhpSpreadsheet\Spreadsheet
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function validarFicheroAmort($fichero)
	{
		$Cabecera = ["A" => "EDIFICIO",
			"B" => "CIAS",
			"C" => "OBSERVACIONES",
			"D" => "FECHA"];

		$file = "upload/" . $fichero->getClientOriginalName();
		$PHPExcel = IOFactory::load($file);

		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$headingsArray = $objWorksheet->rangeToArray('A1:D1', null, true, true, true);

		if ($headingsArray[1] != $Cabecera) {
			dump($Cabecera);
			dump($headingsArray[1]);
			die();
		}

		return $PHPExcel;
	}

	/**
	 * @param $CargaFichero
	 * @param $PHPExcel
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function asignarCeco($CargaFichero, $PHPExcel)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza_repo = $em->getRepository("CostesBundle:Plaza");
		$Ceco_repo = $em->getRepository("CostesBundle:Ceco");

		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$highestRow = $objWorksheet->getHighestRow();

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
			$fechaInicio = new DateTime($fInicio);
			$fechaFin = $fechaInicio->sub(new DateInterval('P1D'));

			if ($Plaza->getCecoActual() != null) {
				$ServicioLog->setMensaje("CERRADO CECOCIAS CIAS= (" . $Plaza->getCias() . ") "
					. ' CECO =(' . $Plaza->getCecoActual()->getCodigo() . ') '
					. ' FECHA= (' . $fechaFin->format('d/m/Y') . ') ');
				$ServicioLog->escribeLog($ficheroLog);
				$CecoCias = $this->selectCecoCias($Plaza);
				$CecoCias->setFFin($fechaFin);
				$em->persist($CecoCias);
				$em->flush();
			}

			$CecoCias = new CecoCias();
			$CecoCias->setPlaza($Plaza);
			$CecoCias->setCeco($Ceco);
			$CecoCias->setFInicio($fechaInicio);
			$ServicioLog->setMensaje("CREADO CECOCIAS CIAS= (" . $Plaza->getCias() . ") CECO =(" . $Ceco->getCodigo() . ") FECHA= (" . $fechaInicio->format('d/m/Y') . ")");
			$ServicioLog->escribeLog($ficheroLog);
			$em->persist($CecoCias);
			$em->flush();

			$ServicioLog->setMensaje('ASIGNADO CECO ACTUAL : ' . $Ceco->getCodigo() . " A PLAZA CIAS: " . $Plaza->getCias());
			$ServicioLog->escribeLog($ficheroLog);
			$Plaza->setCecoActual($Ceco);
			/** @noinspection PhpParamsInspection */
			$em->persist($Plaza);
			$em->flush();
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

		$params = ["CargaFichero" => $CargaFichero,
			"resultado" => $error];
		return $this->render("finCarga.html.twig", $params);
	}

	/**
	 * @param $id
	 * @param $actuacion
	 * @param $cias
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sincroAction($id, $actuacion, $cias)
	{
		$em = $this->getDoctrine()->getManager();
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
		$Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$SincroLog->setUsuario($Usuario);
		$SincroLog->setTabla("ccap_plaza");
		$SincroLog->setIdElemento($id);
		$SincroLog->setFechaProceso($fechaProceso);
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);
		$em->flush();
		if ('DELETE' != $actuacion) {
			$Plaza->setSincroLog($SincroLog);
			$em->persist($Plaza);
			$em->flush();
		}


		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');

		if ('DELETE' === $actuacion) {
			$php_script = $php . " " . $root . "/scripts/costes/deletePlaza.php " . $modo . "  " . $cias;
		} else {
			$php_script = $php . " " . $root . "/scripts/costes/actualizacionPlaza.php " . $modo . "  " . $id . " " . $actuacion;
		}

		exec($php_script, $SALIDA, $resultado);
		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		}
		$ficheroLog = 'sincroPlaza-' . $cias . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('ccap_plaza->cias:' . $cias);

		foreach ($SALIDA as $linea) {
			$ServicioLog->setMensaje($linea);
			$ServicioLog->escribeLog($ficheroLog);
		}
		$SincroLog->setScript($php_script);
		$SincroLog->setFicheroLog($ServicioLog->getFilename());
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);
		$em->flush();

		$params = ["SincroLog" => $SincroLog,
			"resultado" => $resultado];
		$view = $this->renderView("finSincro.html.twig", $params);

		$response = new Response($view);

		$response->headers->set('Content-Disposition', 'inline');
		$response->headers->set('Content-Type', 'text/html');
		$response->headers->set('target', '_blank');

		return $response;
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function descargaLogAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);
		$params = ["id" => $Plaza->getSincroLog()->getId()];
		return $this->redirectToRoute("descargaSincroLog", $params);
	}

	/**
	 * @param $datatable
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function exportarAction()
	{
		$em = $this->getDoctrine()->getManager();
		$PlazaAll = $em->getRepository("CostesBundle:Plaza")->findAll();

		$PHPExcel = new Spreadsheet();
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
		$writer = new Xlsx($PHPExcel);
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

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param $plaza_id
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function verCecoCiasAction(Request $request, $plaza_id)
	{
		$isAjax = $request->isXmlHttpRequest();
		$datatable = $this->get('sg_datatables.factory')->create(CecoCiasDatatable::class);
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

		$params = ['datatable' => $datatable,
			'plaza' => $Plaza];
		return $this->render('costes/cecocias/query.html.twig', $params);
	}
	/**
	 * @param $cias
	 * @param $nuevoCeco
	 * @param $fInicio
	 * @param $cecoAnterior_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
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

		$params = ['id' => $CecoCiasNew->getId()];
		return $this->redirectToRoute("sincroCecoCias", $params);
	}

	/**
	 * @param $Plaza
	 * @return mixed
	 */
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

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sincroCecoCiasAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$CecoCias = $em->getRepository("CostesBundle:CecoCias")->find($id);
		$Plaza = $CecoCias->getPlaza();

		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$SincroLog->setUsuario($Usuario);
		$SincroLog->setTabla("ccap_plaza");
		$SincroLog->setIdElemento($id);
		$SincroLog->setFechaProceso($fechaProceso);
		$SincroLog->setEstado($Estado);


		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/actualizacionCecoCias.php " . $modo . "  " . $id;
		$SALIDA = [];
		exec($php_script, $SALIDA, $resultado);
		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		}

		$ficheroLog = 'sincroCecoCias-' . $CecoCias->getPlaza()->getCias() . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('ccap_plaza->Ceco:' . $CecoCias->getCeco()->getCodigo()
			. ' Cias: ' . $CecoCias->getPlaza()->getCias()
			. ' Fecha :' . $CecoCias->getFInicio()->format('d/m/Y'));
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

		$params = ["SincroLog" => $SincroLog,
			"resultado" => $resultado];
		$view = $this->renderView("finSincro.html.twig", $params);

		$response = new Response($view);
		$response->headers->set('Content-Disposition', 'inline');
		$response->headers->set('Content-Type', 'text/html');
		$response->headers->set('target', '_blank');

		return $response;
	}
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function amortizacionPlazaAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza = $em->getRepository("CostesBundle:Plaza")->find($id);

		$form = $this->createForm(AmortizacionPlazaType::class, $Plaza);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			$ok = $this->compruebaAmortizacion($Plaza);
			if ($ok == 0) {
				try {
					$Plaza->setAmortizada('S');
					$em->persist($Plaza);
					$em->flush();
					$params = ["id" => $Plaza->getId(),
						"actuacion" => "AMORTIZACION",
						"cias" => $Plaza->getCias()];
					return $this->redirectToRoute("sincroPlaza", $params);
				} catch (DBALException $ex) {
					$status = "ERROR GENERAL=" . $ex->getMessage();
					$this->sesion->getFlashBag()->add("status", $status);
					return $this->redirectToRoute("queryPlaza");
				}
			} else {
				$Plaza->setFAmortiza(null);
			}
		}

		$params = ["form" => $form->createView(),
			"plaza" => $Plaza,
			"accion" => "AMORTIZACION"];
		return $this->render("costes/plaza/amortiza.html.twig", $params);
	}

	/**
	 * @param $cias
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function desAmortizacionAction($cias)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza = $em->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);

		try {
			$Plaza->setFAmortiza(null);
			$Plaza->setAmortizada('N');
			/** @noinspection PhpParamsInspection */
			$em->persist($Plaza);
			$em->flush();
			$params = ["id" => $Plaza->getId(),
				"actuacion" => "UPDATE",
				"cias" => $Plaza->getCias()];
			return $this->redirectToRoute("sincroPlaza", $params);
		} catch (DBALException $ex) {
			$status = "ERROR GENERAL=" . $ex->getMessage();
			$this->sesion->getFlashBag()->add("status", $status);
			$params = ["id" => $Plaza->getId()];
			return $this->redirectToRoute("editPlaza", $params);
		}
	}

	/**
	 * @param $Plaza
	 * @return mixed
	 */
	public function compruebaAmortizacion($Plaza)
	{
		$em = $this->getDoctrine()->getManager();
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/compruebaAmortizacionPlaza.php " . $modo . " " . $Plaza->getCias() . " " . $Plaza->getFAmortiza()->format('Y-m-d');
		exec($php_script, $SALIDA, $resultado);
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

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function amortizacionMasivaAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$ImportarForm = $this->createForm(ImportarType::class);
		$ImportarForm->handleRequest($request);
		if ($ImportarForm->isSubmitted()) {
			$file = $ImportarForm["fichero"]->getData();
			if (!empty($file) && $file != null) {
				$file_name = $file->getClientOriginalName();
				$file->move("upload", $file_name);
				try {
					$PHPExcel = $this->validarFicheroAmort($file);
					$CargaFichero = new CargaFichero();
					$fecha = new DateTime();
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
				} catch (Exception $e) {
					$status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
					$this->sesion->getFlashBag()->add("status", $status);
					$params = ["form" => $ImportarForm->createView()];
					return $this->render("costes/plaza/amortizacion.masiva.html.twig", $params);
				}
			}
		}
		$params = ["form" => $ImportarForm->createView()];
		return $this->render("costes/plaza/amortizacion.masiva.html.twig", $params);
	}

	/**
	 * @param $CargaFichero
	 * @param $PHPExcel
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function amortizaPlazaFichero($CargaFichero, $PHPExcel)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza_repo = $em->getRepository("CostesBundle:Plaza");

		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$highestRow = $objWorksheet->getHighestRow();
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
			$headingsArray = $objWorksheet->rangeToArray('A' . $i . ':D' . $i, null, true, true, true);
			$headingsArray = $headingsArray[$i];
			$cias = $headingsArray["B"];
			$observaciones = $headingsArray["C"];
			$fecha = $headingsArray["D"];
			$fechaAmortizacion = new \DateTime($fecha);

			$Plaza = $Plaza_repo->findPlazaByCias($cias);
			$ServicioLog->setLogger('TRATAMIENTO CIAS: ' . $cias);
			$ServicioLog->setMensaje('=> AMORTIZACIÓN CIAS: (' . $cias . ') '
				. ' OBSERVA: (' . $observaciones . ') '
				. ' FECHA: (' . $fechaAmortizacion->format('Y-m-d') . ')');
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
			$SALIDA = [];
			exec($php_script, $SALIDA, $resultado);
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
				$php_script = $php . " " . $root . "/scripts/costes/actualizacionPlaza.php " . $modo . "  " . $Plaza->getId() . " AMORTIZACION";
				exec($php_script, $SALIDA2, $resultado);
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

		$params = ["CargaFichero" => $CargaFichero,
			"resultado" => $error];
		return $this->render("finCarga.html.twig", $params);
	}

	/**
	 * @param $cias
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($cias)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza = $em->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);

		$ok = $this->compruebaDelete($Plaza);
		if ($ok == 0) {
			try {

				$params = ["id" => $Plaza->getId(),
					"actuacion" => "DELETE",
					"cias" => $Plaza->getCias()];
				$em->remove($Plaza);
				$em->flush();
				return $this->redirectToRoute("sincroPlaza", $params);

			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
				return $this->redirectToRoute("queryPlaza");
			}
		}

		$params = ['id' => $Plaza->getId()];
		return $this->redirectToRoute("editPlaza", $params);
	}

	/**
	 * @param $Plaza
	 * @return mixed
	 */
	public function compruebaDelete($Plaza)
	{
		$em = $this->getDoctrine()->getManager();
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/compruebaDeletePlaza.php " . $modo . " " . $Plaza->getCias();
		exec($php_script, $SALIDA, $resultado);
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

			$linea = " ERROR EXISTEN ALTAS PARA ESTE CIAS";
			$this->sesion->getFlashBag()->add("status", $linea);
		}

		return $resultado;
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerTurnoAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$TurnoAll = $em->getRepository("MaestrosBundle:Turno")->createQueryBuilder('u')
			->where('u.id = :id')
			->setParameter('id', $id)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
		$Turno = $TurnoAll[0];
		$response = new Response();
		$response->setContent(json_encode($Turno));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $codigo
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerUfAction($codigo)
	{
		$em = $this->getDoctrine()->getManager();
		$UfAll = $em->getRepository("CostesBundle:Uf")->createQueryBuilder('u')
			->where('u.oficial = :codigo')
			->setParameter('codigo', $codigo)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
		if ($UfAll) {
			$Uf = $UfAll[0];
		} else {
			$Uf["codigo"] = $codigo;
			$Uf['descripcion'] = " NO EXITE UNIDAD FUNCIONAL";
		}
		$response = new Response();
		$response->setContent(json_encode($Uf));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $codigo
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerPaAction($codigo)
	{
		$em = $this->getDoctrine()->getManager();
		$PaAll = $em->getRepository("CostesBundle:Pa")->createQueryBuilder('u')
			->where('u.oficial = :codigo')
			->setParameter('codigo', $codigo)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
		if ($PaAll) {
			$Pa = $PaAll[0];
		} else {
			$Pa["codigo"] = $codigo;
			$Pa['descripcion'] = " NO EXITE PUNTO ASISTTENCIAL";
		}
		$response = new Response();
		$response->setContent(json_encode($Pa));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerUfByIdAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UfAll = $em->getRepository("CostesBundle:Uf")->createQueryBuilder('u')
			->where('u.id = :id')
			->setParameter('id', $id)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
		if ($UfAll) {
			$Uf = $UfAll[0];
		} else {
			$Uf["oficial"] = null;
			$Uf['descripcion'] = " NO EXITE UNIDAD FUNCIONAL";
		}
		$response = new Response();
		$response->setContent(json_encode($Uf));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerPaByIdAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$PaAll = $em->getRepository("CostesBundle:Pa")->createQueryBuilder('u')
			->where('u.id = :id')
			->setParameter('id', $id)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
		if ($PaAll) {
			$Pa = $PaAll[0];
		} else {
			$Pa["oficial"] = null;
			$Pa['descripcion'] = " NO EXITE PUNTO ASISTENCIAL";
		}
		$response = new Response();
		$response->setContent(json_encode($Pa));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function cambioAdscripcionAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$ImportarForm = $this->createForm(ImportarType::class);
		$ImportarForm->handleRequest($request);
		if ($ImportarForm->isSubmitted()) {
			$file = $ImportarForm["fichero"]->getData();
			if (!empty($file) && $file != null) {
				$file_name = $file->getClientOriginalName();
				$file->move("upload", $file_name);
				try {
					$PHPExcel = $this->validarFicheroCambioAds($file);
					$CargaFichero = new CargaFichero();
					$fecha = new DateTime();
					$CargaFichero->setFechaCarga($fecha);
					$CargaFichero->setDescripcion("CAMBIO MASIVO DE ADSCRIPCIONES DE PLAZAS");
					$CargaFichero->setFichero($file_name);
					$CargaFichero->setTabla("CCAP_PLAZAS(ADSCRIPCIÓN)");
					$usuario_id = $this->sesion->get('usuario_id');
					$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
					$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
					$CargaFichero->setUsuario($Usuario);
					$CargaFichero->setEstadoCargaInicial($Estado);
					$em->persist($CargaFichero);
					$em->flush();
					return $this->cambiaAdscripcionPlaza($CargaFichero, $PHPExcel);
				} catch (Exception $e) {
					$status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
					$this->sesion->getFlashBag()->add("status", $status);
					$params = ["form" => $ImportarForm->createView()];
					return $this->render("costes/plaza/cambio.adscripcion.html.twig", $params);
				}
			}
		}
		$params = ["form" => $ImportarForm->createView()];
		return $this->render("costes/plaza/cambio.adscripcion.html.twig", $params);
	}

	public function validarFicheroCambioAds($fichero)
	{
		$Cabecera = ["A" => "CIAS",
			"B" => "UF",
			"C" => "PA"];

		$file = "upload/" . $fichero->getClientOriginalName();
		$PHPExcel = IOFactory::load($file);

		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$headingsArray = $objWorksheet->rangeToArray('A1:C1', null, true, true, true);

		if ($headingsArray[1] != $Cabecera) {
			dump($Cabecera);
			dump($headingsArray[1]);
			die();
		}

		return $PHPExcel;
	}

	public function cambiaAdscripcionPlaza($CargaFichero, $PHPExcel)
	{
		$em = $this->getDoctrine()->getManager();

		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$highestRow = $objWorksheet->getHighestRow();
		$ficheroLog = 'cambioAdscripcion-' . $CargaFichero->getId() . '.log';
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
			$headingsArray = $objWorksheet->rangeToArray('A' . $i . ':C' . $i, null, true, true, true);
			$headingsArray = $headingsArray[$i];
			$cias = $headingsArray["A"];
			$codigoUf = $headingsArray["B"];
			$codigoPa = $headingsArray["C"];

			$Plaza = $em->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);
			$ServicioLog->setLogger('CIAS: ' . $cias);

			if ($Plaza == null) {
				$ServicioLog->setMensaje("**ERROR NO EXISTE PLAZA PARA EL CIAS: " . $cias);
				$ServicioLog->escribeLog($ficheroLog);
				$error = 1;
				continue;
			}

			$Uf = $em->getRepository('CostesBundle:Uf')->findUfByOficial($codigoUf);
			if ($Uf == null) {
				$ServicioLog->setMensaje("**ERROR NO EXISTE UNIDAD FUNCIONAL PARA EL CODIGO :(" . $codigoUf . ') ');
				$ServicioLog->escribeLog($ficheroLog);
				$error = 1;
				continue;
			}

			$Pa = $em->getRepository('CostesBundle:Pa')->findPaByOficial($codigoPa);
			if ($Pa == null) {
				$ServicioLog->setMensaje("**ERROR NO EXISTE PUNTO ASISTENCIAL PARA EL CODIGO :(" . $codigoPa . ') ');
				$ServicioLog->escribeLog($ficheroLog);
				$error = 1;
				continue;
			}


			$ServicioLog->setMensaje('Unidad Funcional Anterior: (' . $Plaza->getUf()->getOficial() . ') (' . $Plaza->getUf()->getDescripcion() . ') ');
			$ServicioLog->escribeLog($ficheroLog);
			$ServicioLog->setMensaje('Punto Asistencial Anterior: (' . $Plaza->getPa()->getOficial() . ') (' . $Plaza->getPa()->getDescripcion() . ') ');
			$ServicioLog->escribeLog($ficheroLog);
			$ServicioLog->setMensaje('Unidad Funcional Nuevo: (' . $Uf->getOficial() . ') (' . $Uf->getDescripcion() . ') ');
			$ServicioLog->escribeLog($ficheroLog);
			$ServicioLog->setMensaje('Punto Asistencial Nuevo: (' . $Pa->getOficial() . ') (' . $Pa->getDescripcion() . ') ');
			$ServicioLog->escribeLog($ficheroLog);

			$Plaza->setUf($Uf);
			$Plaza->setPa($Pa);
			$em->persist($Plaza);
			$em->flush();

			$root = $this->get('kernel')->getRootDir();
			$modo = $this->getParameter('modo');
			$php = $this->getParameter('php');
			$php_script = $php . " " . $root . "/scripts/costes/actualizacionPlaza.php " . $modo . "  " . $Plaza->getId() . " UPDATE";
			dump($php_script);

			$SALIDA = [];
			exec($php_script, $SALIDA, $resultado);
			if ($resultado != 0) {
				dump($resultado);
				die();
			}
			foreach ($SALIDA as $linea) {
				$ServicioLog->setLogger('Sincro-Plaza CIAS: ' . $cias);
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

		$params = ["CargaFichero" => $CargaFichero,
			"resultado" => $error];
		return $this->render("finCarga.html.twig", $params);
	}

	public function consultaAltasAction(Request $request, $cias)
	{
		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/consultaAltasByCias.php " . $modo . " " . $cias;

		exec($php_script, $SALIDA, $resultado);

		$Plaza = $this->getDoctrine()->getManager()->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);

		$isAjax = $request->isXmlHttpRequest();
		$datatableAltas = $this->get('sg_datatables.factory')->create(TempAltasDatatable::class);
		$datatableAltas->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatableAltas);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$datatableQueryBuilder->buildQuery();

			return $responseService->getResponse();
		}

		$params = ["plaza" => $Plaza,
			"datatable" => $datatableAltas];
		return $this->render("costes/plaza/consultaAltas.html.twig", $params);
	}

}
