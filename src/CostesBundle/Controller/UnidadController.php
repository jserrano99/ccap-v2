<?php

namespace CostesBundle\Controller;


use ComunBundle\Entity\SincroLog;
use CostesBundle\Entity\ResponsableUnidad;
use CostesBundle\Entity\UnidadOrganizativa;
use CostesBundle\Entity\ValidadorUnidad;
use CostesBundle\Form\AsignarPlazaType;
use CostesBundle\Form\UnidadOrganizativaType;
use DateInterval;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class UnidadController
 *
 * @package CostesBundle\Controller
 */
class UnidadController extends Controller
{
	/**
	 * @var Session
	 */
	private $sesion;

	/**
	 * UnidadController constructor.
	 */
	public function __construct()
	{
		$this->sesion = new Session();
	}

	/**
	 * @return Response
	 * @throws \Exception
	 */
	public function queryEstructuraAction()
	{
		return $this->render('costes/unidad/query.html.twig');
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		$EM = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = new UnidadOrganizativa();

		$form = $this->createForm(UnidadOrganizativaType::class, $UnidadOrganizativa);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				$EM->persist($UnidadOrganizativa);
				$EM->flush();
				$params = ["id" => $UnidadOrganizativa->getId(),
					"actuacion" => "INSERT"];
				return $this->redirectToRoute("sincroUnidad", $params);
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["unidadOrganizativa" => $UnidadOrganizativa,
			"accion" => "NUEVA",
			'responsableUnidadAll' => null,
			'validadoresJanoAll' => null,
			"form" => $form->createView()];
		return $this->render("costes/unidad/edit.html.twig", $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param                                           $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function agregarAction(Request $request, $id)
	{
		$EM = $this->getDoctrine()->getManager();
		$UnidadOrganizativaDep = $EM->getRepository("CostesBundle:UnidadOrganizativa")->find($id);
		$UnidadOrganizativa = new UnidadOrganizativa();
		$UnidadOrganizativa->setDependencia($UnidadOrganizativaDep);
		$form = $this->createForm(UnidadOrganizativaType::class, $UnidadOrganizativa);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				$EM->persist($UnidadOrganizativa);
				$EM->flush();
				$params = ["id" => $UnidadOrganizativa->getId(),
					"actuacion" => "INSERT"];
				return $this->redirectToRoute("sincroUnidad", $params);
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}
		$params = ["unidadOrganizativa" => $UnidadOrganizativa,
			"accion" => "NUEVA",
			'responsableUnidadAll' => null,
			'validadoresJanoAll' => null,
			"form" => $form->createView()];

		return $this->render("costes/unidad/edit.html.twig", $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param  int                                         $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $entityManager->getRepository("CostesBundle:UnidadOrganizativa")->find($id);

		$form = $this->createForm(UnidadOrganizativaType::class, $UnidadOrganizativa);
		$form->handleRequest($request);

		$ResponsableUnidadAll = $entityManager->getRepository("CostesBundle:ResponsableUnidad")->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa')
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult();;

		$ValidadoresJanoAll = $entityManager->getRepository("CostesBundle:ValidadorUnidad")->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa')
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult();;


		if ($form->isSubmitted()) {
			try {
				$entityManager->persist($UnidadOrganizativa);
				$entityManager->flush();
				$params = ["id" => $UnidadOrganizativa->getId(),
					"actuacion" => "UPDATE"];
				return $this->redirectToRoute("sincroUnidad", $params);

			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["unidadOrganizativa" => $UnidadOrganizativa,
			"accion" => "MODIFICACION",
			'responsableUnidadAll' => $ResponsableUnidadAll,
			'validadoresJanoAll' => $ValidadoresJanoAll,
			"form" => $form->createView()];
		return $this->render("costes/unidad/edit.html.twig", $params);
	}

	/**
	 * @param int $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id)
	{
		$EM = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $EM->getRepository("CostesBundle:UnidadOrganizativa")->find($id);

		$params = ["id" => $UnidadOrganizativa->getId(),
			"actuacion" => "DELETE"];

		return $this->redirectToRoute("sincroUnidad", $params);

	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function ajaxGetUnidadAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->createQueryBuilder('u')
			->where('u.id = :id')
			->setParameter('id', $id)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;

		$response = new Response();
		$response->setContent(json_encode($UnidadOrganizativa[0]));
		$response->headers->set("Content-type", "application/json");
		return $response;

	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxGetEstructuraAction()
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativaAll = $em->getRepository("CostesBundle:UnidadOrganizativa")->createQueryBuilder('u')
			->where('u.dependencia is null ')
			->orderBy('u.orden', 'asc')
			->getQuery()->getResult(Query::HYDRATE_ARRAY);
		$data = [];

		foreach ($UnidadOrganizativaAll as $row) {
			$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($row["id"]);

			$PlazaAll = $em->getRepository("CostesBundle:Plaza")->createQueryBuilder('u')
				->where('u.unidadOrganizativa = :unidadOrganizativa ')
				->setParameter('unidadOrganizativa', $UnidadOrganizativa)
				->getQuery()->getResult(Query::HYDRATE_ARRAY);

			foreach ($PlazaAll as $Plaza) {
				$sub_data["text"] = $Plaza["cias"];
				$sub_data["tipo"] = "cias";
				$sub_data["icon"] = "glyphicon glyphicon-user";
				$data[] = $sub_data;
			}

			$sub_data["tipo"] = "unidad";
			$sub_data["unidad"] = $row["id"];
			$sub_data["name"] = $row["codigo"];
			$sub_data["text"] = $row["codigo"] . " " . $row["descripcion"];
			$sub_data["nodes"] = $this->verDependientes($row["id"]);
			$data[] = $sub_data;
		}
		$response = new Response();
		$response->setContent(json_encode($data));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $id
	 * @return array
	 */
	public function verDependientes($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);
		$data = [];

		$PlazaAll = $em->getRepository("CostesBundle:Plaza")->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa ')
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);

		foreach ($PlazaAll as $Plaza) {
			if ($UnidadOrganizativa->getResponsableActual()) {
				if ($Plaza["cias"] != $UnidadOrganizativa->getResponsableActual()->getCias()) {
					$sub_data["text"] = $Plaza["cias"];
					$sub_data["cias"] = $Plaza["cias"];
					$sub_data["tipo"] = "cias";
					$sub_data["icon"] = "glyphicon glyphicon-user";
					$sub_data["color"] = "#000000";
					$data[] = $sub_data;
				}
			} else {
				$sub_data["text"] = $Plaza["cias"];
				$sub_data["cias"] = $Plaza["cias"];
				$sub_data["tipo"] = "cias";
				$sub_data["icon"] = "glyphicon glyphicon-user";
				$sub_data["color"] = "#000000";
				$data[] = $sub_data;
			}
		}

		$UnidadOrganizativaAll = $em->getRepository("CostesBundle:UnidadOrganizativa")->createQueryBuilder('u')
			->where('u.dependencia = :dependencia ')
			->setParameter('dependencia', $UnidadOrganizativa)
			->orderBy('u.orden', 'asc')
			->getQuery()->getResult(Query::HYDRATE_ARRAY);


		foreach ($UnidadOrganizativaAll as $row) {
			$sub_data["unidad"] = $row["id"];
			$sub_data["tipo"] = "unidad";
			$sub_data["cias"] = "";
			$sub_data["name"] = $row["codigo"];
			$sub_data["text"] = $row["codigo"] . " " . $row["descripcion"];
			$sub_data["icon"] = "";
			$sub_data["color"] = "#000000";
			$sub_data["nodes"] = $this->verDependientes($row["id"]);
			if (count($sub_data["nodes"]) == 0) unset($sub_data["nodes"]);
			$data[] = $sub_data;
		}
		return $data;
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerDependenciaAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);
		$sub_data["id"] = $UnidadOrganizativa->getId();
		$sub_data["unidad"] = $UnidadOrganizativa->getDescripcion();
		$sub_data["dependencia"] = $this->verDependencia($id);
		if (count($sub_data["dependencia"]) == 0) unset ($sub_data["dependencia"]);
		$data[] = $sub_data;

		$response = new Response();
		$response->setContent(json_encode($data));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param $cias
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxVerDependenciaCiasAction($cias)
	{
		$em = $this->getDoctrine()->getManager();
		$Plaza = $em->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);
		$UnidadOrganizativa = $Plaza->getUnidadOrganizativa();
		$data = [];
		$fecha = date('Y') . '-' . date('m') . '-' . date('d');

		if ($UnidadOrganizativa) {
			$sub_data["id"] = $UnidadOrganizativa->getId();
			$sub_data["unidad"] = $UnidadOrganizativa->getDescripcion();
			if ($UnidadOrganizativa->getResponsable()) {
				$responsable = $this->consultaPersonaByCias($UnidadOrganizativa->getResponsable()->getCias(), $fecha);
				$sub_data["responsableCias"] = $UnidadOrganizativa->getResponsable()->getCias();
				$sub_data["responsableNombre"] = $responsable["nombre"];
				$sub_data["responsableNIF"] = $responsable["dni"];
				$sub_data["responsableCIP"] = $responsable["cip"];
			} else {
				$sub_data["responsableCias"] = "";
				$sub_data["responsableNombre"] = "";
				$sub_data["responsableNIF"] = "";
				$sub_data["responsableCIP"] = "";
			}
			$sub_data["validadores"] = $this->verValidadores($UnidadOrganizativa->getId());
			$sub_data["dependencia"] = $this->verDependencia($UnidadOrganizativa->getId());
			if (count($sub_data["dependencia"]) == 0) unset ($sub_data["dependencia"]);
			$data[] = $sub_data;
		}

		$response = new Response();
		$response->setContent(json_encode($data));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param int $id
	 * @return array
	 */

	public function verDependencia($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);
		$data = [];
		$fecha = date('Y') . '-' . date('m') . '-' . date('d');
		if ($UnidadOrganizativa->getDependencia() !== null) {
			$sub_data["id"] = $UnidadOrganizativa->getDependencia()->getId();
			$sub_data["unidad"] = $UnidadOrganizativa->getDependencia()->getDescripcion();
			if ($UnidadOrganizativa->getDependencia()->getResponsable()) {
				$responsable = $this->consultaPersonaByCias($UnidadOrganizativa->getDependencia()->getResponsable()->getCias(), $fecha);
				$sub_data["responsableCias"] = $UnidadOrganizativa->getDependencia()->getResponsable()->getCias();
				$sub_data["responsableNombre"] = $responsable["nombre"];
				$sub_data["responsableNIF"] = $responsable["dni"];
				$sub_data["responsableCIP"] = $responsable["cip"];
			} else {
				$sub_data["responsableCias"] = "";
				$sub_data["responsableNombre"] = "";
				$sub_data["responsableNIF"] = "";
				$sub_data["responsableCIP"] = "";
			}
			$sub_data["dependencia"] = $this->verDependencia($UnidadOrganizativa->getDependencia()->getId());
			if (count($sub_data["dependencia"]) == 0) unset ($sub_data["dependencia"]);
			$data[] = $sub_data;
		}

		return $data;
	}

	/**
	 * @param $unidad_organizativa_id
	 * @return array
	 */

	public function verValidadores($unidad_organizativa_id) {
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($unidad_organizativa_id);
		$data = [];
		$fecha = date('Y') . '-' . date('m') . '-' . date('d');

		$ValidadoresALL = $em->getRepository("CostesBundle:ValidadorUnidad")->createQueryBuilder('u')
				->where('u.unidadOrganizativa = :unidadOrganizativa')
				->andWhere('u.fInicio <= :fecha ')
				->andWhere('u.fFin is null or u.fFin >= :fecha')
				->setParameter('unidadOrganizativa',$UnidadOrganizativa)
				->setParameter('fecha',$fecha)
				->getQuery()->getResult();
		foreach ( $ValidadoresALL as $ValidadorUnidad) {
			$sub_data["validadorCias"] = $ValidadorUnidad->getPlaza()->getCias();
			$data[] = $sub_data;
		}

		return $data;
	}
	/**
	 * @param  string $cias
	 * @param         $fecha
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxConsultaResposableAction($cias, $fecha)
	{
		$TempAltas = $this->consultaPersonaByCias($cias, $fecha);
		$response = new Response();
		$response->setContent(json_encode($TempAltas));
		$response->headers->set("Content-type", "application/json");
		return $response;
	}

	/**
	 * @param string $cias
	 * @param string $fecha
	 * @return array
	 */
	public function consultaPersonaByCias($cias, $fecha)
	{
		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/consultaPersonaByCias.php " . $modo . " " . $cias . " " . $fecha;

		exec($php_script, $SALIDA, $resultado);

		$TempAltas = $this->getDoctrine()->getManager()
			->getRepository("CostesBundle:TempAltas")->createQueryBuilder('u')
			->getQuery()->getResult(Query::HYDRATE_ARRAY);

		if ($TempAltas)
			return $TempAltas[0];
		else
			return null;

	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param  int                                      $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function agregarPlazaAction(Request $request, $id)
	{
		$EM = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $EM->getRepository("CostesBundle:UnidadOrganizativa")->find($id);

		$form = $this->createForm(AsignarPlazaType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				$Plaza = $EM->getRepository("CostesBundle:Plaza")->findPlazaByCias($form->get("cias")->getData());
				if ($Plaza) {
					$Plaza->setUnidadOrganizativa($UnidadOrganizativa);
					$EM->persist($Plaza);
					$EM->flush();
					$params = ["cias" => $Plaza->getCias()];
					return $this->redirectToRoute("sincroAsignacion", $params);
				} else {
					$status = "ERROR NO EXISTE CIAS ";
					$this->sesion->getFlashBag()->add("status", $status);
				}
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["unidadOrganizativa" => $UnidadOrganizativa, "accion" => "NUEVA", "form" => $form->createView()];
		return $this->render("costes/unidad/agregarPlaza.html.twig", $params);

	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function imprimirEstructuraAction()
	{
		$format = 'pdf';
		$params = [];
		$reportUnit = '/reports/Estructur';
		return $this->get('yoh.jasper.report')->generate($reportUnit, $params, $format);

	}

	/**
	 * @param $unidadOrganizativa_id
	 * @param $ciasResponsable
	 * @param $fcCambio
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function cambioResponsableAction($unidadOrganizativa_id, $ciasResponsable, $fcCambio)
	{
		$entityManager = $this->getDoctrine()->getManager();

		$UnidadOrganizativa = $entityManager->getRepository('CostesBundle:UnidadOrganizativa')->find($unidadOrganizativa_id);
		$ResponsableUnidadActual = $entityManager->getRepository('CostesBundle:ResponsableUnidad')->findResponsableActual($UnidadOrganizativa);
		$Solapados = $entityManager->getRepository('CostesBundle:ResponsableUnidad')->findSolapados($UnidadOrganizativa, $fcCambio);


		if ($Solapados) {
			$status = "ERROR EXISTE SOLAPAMIENTO DE FECHAS PARA " . $fcCambio . " REVISELO";
			$this->sesion->getFlashBag()->add("status", $status);
			return $this->redirectToRoute('editUnidad', ['id' => $unidadOrganizativa_id]);
		}

		$PlazaNueva = $entityManager->getRepository('CostesBundle:Plaza')->findPlazaByCias($ciasResponsable);
		if ($ResponsableUnidadActual) {

			$fInicio = new DateTime($fcCambio);
			$fFin = $fInicio->sub(new DateInterval('P1D'));
			$ResponsableUnidadActual->setFFin($fFin);
			$entityManager->persist($ResponsableUnidadActual);
			$entityManager->flush();
		}
		$fInicio = new DateTime($fcCambio);
		$ResponsableUnidad = new ResponsableUnidad();
		$ResponsableUnidad->setUnidadOrganizativa($UnidadOrganizativa);
		$ResponsableUnidad->setPlaza($PlazaNueva);
		$ResponsableUnidad->setFInicio($fInicio);
		$entityManager->persist($ResponsableUnidad);
		$entityManager->flush();

		$UnidadOrganizativa->setResponsableActual($PlazaNueva);
		$entityManager->persist($UnidadOrganizativa);
		$entityManager->flush();

		return $this->redirectToRoute('editUnidad', ['id' => $unidadOrganizativa_id]);
	}

	/**
	 * @param $unidadOrganizativa_id
	 * @param $ciasValidador
	 * @param $fcCambio
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function addValidadorAction($unidadOrganizativa_id, $ciasValidador, $fcCambio)
	{

		$entityManager = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $entityManager->getRepository("CostesBundle:UnidadOrganizativa")->find($unidadOrganizativa_id);
		$Plaza = $entityManager->getRepository("CostesBundle:Plaza")->findPlazaByCias($ciasValidador);
		$fInicio = new DateTime($fcCambio);

		$ValidadorUnidad = new ValidadorUnidad();
		$ValidadorUnidad->setUnidadOrganizativa($UnidadOrganizativa);
		$ValidadorUnidad->setPlaza($Plaza);
		$ValidadorUnidad->setFInicio($fInicio);

		$entityManager->persist($ValidadorUnidad);
		$entityManager->flush();

		return $this->redirectToRoute('editUnidad', ['id' => $unidadOrganizativa_id]);
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function cerrarValidadorAction($id)
	{
		$entityManager = $this->getDoctrine()->getManager();

		$ValidadorUnidad = $entityManager->getRepository("CostesBundle:ValidadorUnidad")->find($id);
		$fFin = new DateTime();

		$ValidadorUnidad->setFFin($fFin);
		$entityManager->persist($ValidadorUnidad);
		$entityManager->flush();
		return $this->redirectToRoute('editUnidad', ['id' => $ValidadorUnidad->getUnidadOrganizativa()->getId()]);

	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteValidadorAction($id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$ValidadorUnidad = $entityManager->getRepository("CostesBundle:ValidadorUnidad")->find($id);
		$unidadOrganizativa_id = $ValidadorUnidad->getUnidadOrganizativa()->getId();
		$entityManager->remove($ValidadorUnidad);
		$entityManager->flush();
		return $this->redirectToRoute('editUnidad', ['id' => $unidadOrganizativa_id]);
	}

	/**
	 * @param $id
	 * @param $actuacion
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function sincroAction($id, $actuacion)
	{
		$em = $this->getDoctrine()->getManager();
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$SincroLog->setUsuario($Usuario);
		$SincroLog->setTabla("ccap_unidad");
		$SincroLog->setIdElemento($id);
		$SincroLog->setFechaProceso($fechaProceso);
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);
		$em->flush();
		if ('DELETE' != $actuacion) {
			$UnidadOrganizativa->setSincroLog($SincroLog);
			$em->persist($UnidadOrganizativa);
			$em->flush();
		}


		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');

		$php_script = $php . " " . $root . "/scripts/costes/actualizacionUnidadOrganizativa.php " . $modo . "  " . $id . " " . $actuacion;

		exec($php_script, $SALIDA, $resultado);
		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		}
		$ficheroLog = 'sincroUnidadOrganizativa-' . $id . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('ccap_unidad:' . $id);

		foreach ($SALIDA as $linea) {
			$ServicioLog->setMensaje($linea);
			$ServicioLog->escribeLog($ficheroLog);
		}
		$SincroLog->setScript($php_script);
		$SincroLog->setFicheroLog($ServicioLog->getFilename());
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);
		$em->flush();

		return $this->redirectToRoute("queryEstructura");


	}

	/**
	 * @param $cias
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @throws \Exception
	 */
	public function sincroAsignacionAction($cias)
	{
		$em = $this->getDoctrine()->getManager();
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
		$Plaza = $em->getRepository("CostesBundle:Plaza")->findPlazaByCias($cias);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$SincroLog->setUsuario($Usuario);
		$SincroLog->setTabla("ccap_plaza");
		$SincroLog->setIdElemento($Plaza->getId());
		$SincroLog->setFechaProceso($fechaProceso);
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);
		$em->flush();

		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');

		$php_script = $php . " " . $root . "/scripts/costes/asignacionUnidadaPlaza.php " . $modo . "  " . $Plaza->getId();

		exec($php_script, $SALIDA, $resultado);
		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		}
		$ficheroLog = 'sincroPlaza-' . $Plaza->getCias() . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('ccap_plaza: cias' . $Plaza->getCias());

		foreach ($SALIDA as $linea) {
			$ServicioLog->setMensaje($linea);
			$ServicioLog->escribeLog($ficheroLog);
		}
		$SincroLog->setScript($php_script);
		$SincroLog->setFicheroLog($ServicioLog->getFilename());
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);
		$em->flush();

		return $this->redirectToRoute("queryEstructura");

	}

}
