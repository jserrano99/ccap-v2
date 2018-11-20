<?php

namespace CostesBundle\Controller;

use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CostesBundle\Form\ImportarType;
use Symfony\Component\HttpFoundation\Session\Session;
use CostesBundle\Entity\Ceco;
use CostesBundle\Form\CecoType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use ComunBundle\Entity\CargaFichero;
use DateTime;
use Doctrine\DBAL\DBALException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ComunBundle\Entity\SincroLog;
use CostesBundle\Datatables\CecoDatatable;


/**
 * Class CecoController
 * @package CostesBundle\Controller
 */
class CecoController extends Controller
{
	/**
	 * @var Session
	 */
	private $sesion;

	/**
	 * CecoController constructor.
	 */
	public function __construct()
	{
		$this->sesion = new Session();
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
	 * @throws \Exception
	 */
	public function queryAction(Request $request)
	{
		$isAjax = $request->isXmlHttpRequest();

		$datatable = $this->get('sg_datatables.factory')->create(CecoDatatable::class);
		$datatable->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatable);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$datatableQueryBuilder->buildQuery();

			return $responseService->getResponse();
		}

		$params = ['datatable' => $datatable];
		return $this->render('costes/ceco/query.html.twig', $params);
	}

//    /**
//     * @param $ceco_id
//     * @return Response
//     */
//    public function verCecoAction($ceco_id) {
//        $em = $this->getDoctrine()->getManager();
//        $Ceco_repo = $em->getRepository("CostesBundle:Ceco");
//        $Ceco = $Ceco_repo->find($ceco_id);
//
//        $params = ["ceco" => $Ceco];
//        return $this->render("costes/ceco/verCeco.html.twig", $params);
//    }

	/**
	 * @param $ceco_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($ceco_id)
	{
		$em = $this->getDoctrine()->getManager();
		$Ceco_repo = $em->getRepository("CostesBundle:Ceco");
		$Ceco = $Ceco_repo->find($ceco_id);

		$params = ["id" => $Ceco->getId(),
			"actuacion" => "DELETE"];
		return $this->redirectToRoute("sincroCeco", $params);
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function addAction(Request $request)
	{
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
				$params = ["id" => $Ceco->getId(),
					"actuacion" => "INSERT"];
				return $this->redirectToRoute("sincroCeco", $params);
			} catch (UniqueConstraintViolationException $ex) {
				$status = " YA EXISTE UN CECO CON ESTE CÓDIGO: " . $Ceco->getCodigo();
				$this->sesion->getFlashBag()->add("status", $status);

			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}
		$params = ["form" => $CecoForm->createView(),
			"ceco" => $Ceco,
			"accion" => "CREACIÓN"];
		return $this->render("costes/ceco/edit.html.twig", $params);
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function editAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$Ceco_repo = $em->getRepository("CostesBundle:Ceco");
		$Ceco = $Ceco_repo->find($id);

		$CecoForm = $this->createForm(CecoType::class, $Ceco);
		$CecoForm->handleRequest($request);

		if ($CecoForm->isSubmitted()) {
			try {
				$em->persist($Ceco);
				$em->flush();
				$params = ["id" => $Ceco->getId(),
					"actuacion" => "UPDATE"];
				return $this->redirectToRoute("sincroCeco", $params);
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}
		$params = ["form" => $CecoForm->createView(),
			"ceco" => $Ceco,
			"accion" => "MODIFICACION"];
		return $this->render("costes/ceco/edit.html.twig", $params);
	}

	/**
	 * @param Request $request
	 * @return Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function importaAction(Request $request)
	{
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

				$CargaFichero = new CargaFichero();
				$fecha = new DateTime();
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

				$params = ["CargaFichero" => $CargaFichero,
					"resultado" => 0];
				return $this->render("finCarga.html.twig", $params);
			}
		}

		$params = ["form" => $ImportarForm->createView()];
		return $this->render("costes/ceco/importar.html.twig", $params);
	}

	/**
	 * @param $fichero
	 * @return null|\PhpOffice\PhpSpreadsheet\Spreadsheet
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function validarFichero($fichero)
	{
		$Cabecera = ["A" => "SOCIEDAD",
			"B" => "DIVISION",
			"C" => "CECO",
			"D" => "DESCRIPCION",
			"E" => "ACTUACION"];

		$file = "upload/" . $fichero->getClientOriginalName();
		$PHPExcel = IOFactory::load($file);
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

	/**
	 * @param $CargaFichero
	 * @param $PHPExcel
	 * @return mixed
	 */
	public function cargaCECO($CargaFichero, $PHPExcel)
	{
		$em = $this->getDoctrine()->getManager();

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
			$Ceco_repo = $em->getRepository("CostesBundle:Ceco");
			$headingsArray = $objWorksheet->rangeToArray('A' . $i . ':E' . $i, null, true, true, true);
			$headingsArray = $headingsArray[$i];

			$sociedad = $headingsArray["A"];
			$division = $headingsArray["B"];
			$codigo = $headingsArray["C"];
			$descripcion = $headingsArray["D"];

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
			$php = $this->getParameter('php');
			$php_script = $php . " " . $root . "/scripts/costes/actualizacionCeco.php " . $modo . "  " . $Ceco->getId() . " INSERT ";

			exec($php_script, $SALIDA, $resultado);
			if ($resultado != 0) $error = 1;
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
			$ServicioLog->setMensaje("==>TERMINA CORRECTAMENTE <===");
			$ServicioLog->escribeLog($ficheroLog);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
			$ServicioLog->setMensaje("==>TERMINA EN ERROR <===");
			$ServicioLog->escribeLog($ficheroLog);
		}

		$CargaFichero->setFicheroLog($ServicioLog->getFilename());
		$CargaFichero->setEstadoCargaInicial($Estado);

		return $CargaFichero;
	}

	/**
	 * @param $id
	 * @param $actuacion
	 * @return Response
	 */
	public function sincroAction($id, $actuacion)
	{
		$em = $this->getDoctrine()->getManager();
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
		$Ceco = $em->getRepository("CostesBundle:Ceco")->find($id);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

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
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/actualizacionCeco.php " . $modo . "  " . $Ceco->getId() . " " . $actuacion;
		$res = exec($php_script, $SALIDA, $resultado);

		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			//$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
			dump($php_script);
			dump($res);
			dump($resultado);
			dump($SALIDA);
			die();
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

		$params = ["SincroLog" => $SincroLog,
			"resultado" => $resultado];
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

	/**
	 * @param $id
	 * @return Response
	 */
	public function ajaxVerCecoAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$Ceco_repo = $em->getRepository("CostesBundle:Ceco");
		$Ceco = $Ceco_repo->createQueryBuilder('u')
			->where('u.id = :id')
			->setParameter('id', $id)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;
		$Ceco = $Ceco[0];
		$response = new Response();
		$response->setContent(json_encode($Ceco));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function descargaLogAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$Ceco = $em->getRepository("CostesBundle:Ceco")->find($id);
		$params = ["id" => $Ceco->getSincroLog()->getId()];
		return $this->redirectToRoute("descargaSincroLog", $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function comprobacionAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$ImportarForm = $this->createForm(ImportarType::class);
		$ImportarForm->handleRequest($request);

		if ($ImportarForm->isSubmitted()) {
			$file = $ImportarForm["fichero"]->getData();
			if (!empty($file) && $file != null) {
				$file_name = $file->getClientOriginalName();
				$file->move("upload", $file_name);
				$file = "upload/" . $file->getClientOriginalName();
				try {
					$PHPExcel = IOFactory::load($file);
					$CargaFichero = new CargaFichero();
					$fecha = new DateTime();
					$CargaFichero->setFechaCarga($fecha);
					$CargaFichero->setDescripcion("COMPROBACIÓN CECO");
					$CargaFichero->setFichero($file_name);
					$CargaFichero->setTabla("CCAP_CECO)");
					$usuario_id = $this->sesion->get('usuario_id');
					$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
					$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
					$CargaFichero->setUsuario($Usuario);
					$CargaFichero->setEstadoCargaInicial($Estado);
					$em->persist($CargaFichero);
					$em->flush();
					return $this->comprobarCeco($CargaFichero, $PHPExcel);
				} catch (Exception $e) {
					$status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
					$this->sesion->getFlashBag()->add("status", $status);
					$params = ["form" => $ImportarForm->createView()];
					return $this->render("costes/ceco/comprobacion.html.twig", $params);
				}
			}
		}

		$params = ["form" => $ImportarForm->createView()];
		return $this->render("costes/ceco/comprobacion.html.twig", $params);
	}

	/**
	 * @param $CargaFichero
	 * @param $PHPExcel
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function comprobarCeco($CargaFichero, $PHPExcel)
	{
		$em = $this->getDoctrine()->getManager();
		$Ceco_repo = $em->getRepository("CostesBundle:Ceco");

		$objWorksheet = $PHPExcel->setActiveSheetIndex(0);
		$highestRow = $objWorksheet->getHighestRow();

		$ficheroLog = 'ComprobacionCeco-' . $CargaFichero->getId() . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('');
		$ServicioLog->setMensaje("==> COMIENZA TRATAMIENTO <== ");
		$ServicioLog->escribeLog($ficheroLog);

		$error = 0;
		for ($i = 2; $i <= $highestRow; $i++) {
			$em = $this->getDoctrine()->getManager();
			if (!$em->isOpen()) {
				$em = $this->getDoctrine()->getManager()->create($em->getConnection(), $em->getConfiguration());
			}
			$headingsArray = $objWorksheet->rangeToArray('A' . $i . ':F' . $i, null, true, true, true);
			$headingsArray = $headingsArray[$i];


			$ceco = $headingsArray["A"];
			$descripcion = $headingsArray["B"];

			$Ceco = $Ceco_repo->findCecoByCodigo($ceco);
			if ($Ceco == null) {
				$ServicioLog->setMensaje('CECO= (' . $ceco . ') '
					. 'DESCRIPCION= (' . $descripcion . ") "
					. "**ERROR NO EXISTE CECO :**");
				$ServicioLog->escribeLog($ficheroLog);
				continue;
			}

			if ($Ceco->getDescripcion() != $descripcion) {
				$ServicioLog->setMensaje('CECO= (' . $ceco . ') '
					. 'DESCRIPCION FICHERO= (' . $descripcion . ") "
					. 'DESCRIPCION CCAP= (' . $Ceco->getDescripcion() . ") "
					. "**DESCRIPCION DIFERENTE**");
				$ServicioLog->escribeLog($ficheroLog);
			}
		}

		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		$ServicioLog->setMensaje("==> TERMINA COMPROBACION <==");
		$ServicioLog->escribeLog($ficheroLog);
		$CargaFichero->setFicheroLog($ServicioLog->getFilename());
		$CargaFichero->setEstadoCargaInicial($Estado);
		$em->persist($CargaFichero);
		$em->flush();

		$params = ["CargaFichero" => $CargaFichero,
			"resultado" => $error];
		return $this->render("finCarga.html.twig", $params);
	}

}
