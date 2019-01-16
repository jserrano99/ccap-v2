<?php

namespace CostesBundle\Controller;


use CostesBundle\Entity\UnidadOrganizativa;
use CostesBundle\Form\AsignarPlazaType;
use CostesBundle\Form\UnidadOrganizativaType;
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
				return $this->redirectToRoute("queryEstructura");
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["unidadOrganizativa" => $UnidadOrganizativa, "accion" => "NUEVA", "form" => $form->createView()];
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
		$UnidadOrganizativa->setCodigo($UnidadOrganizativaDep->getCodigo());
		$form = $this->createForm(UnidadOrganizativaType::class, $UnidadOrganizativa);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				$EM->persist($UnidadOrganizativa);
				$EM->flush();
				return $this->redirectToRoute("queryEstructura");
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["unidadOrganizativa" => $UnidadOrganizativa, "accion" => "NUEVA", "form" => $form->createView()];
		return $this->render("costes/unidad/edit.html.twig", $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param                                           $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, $id)
	{
		$EM = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $EM->getRepository("CostesBundle:UnidadOrganizativa")->find($id);

		$form = $this->createForm(UnidadOrganizativaType::class, $UnidadOrganizativa);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				if ($form->get("responsableCias")->getData() != null) {
					$Plaza = $EM->getRepository("CostesBundle:Plaza")->findPlazaByCias($form->get("responsableCias")->getData());
					$UnidadOrganizativa->setResponsable($Plaza);
				}
				$EM->persist($UnidadOrganizativa);
				$EM->flush();
				return $this->redirectToRoute("queryEstructura");
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["unidadOrganizativa" => $UnidadOrganizativa, "accion" => "MODIFICACIÓN", "form" => $form->createView()];
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

		$EM->remove($UnidadOrganizativa);
		$EM->flush();
		return $this->redirectToRoute("queryEstructura");
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
			->orderBy('u.codigo', 'asc')
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
		$fecha = '2019-01-01';
		if ($UnidadOrganizativa->getResponsable() != null) {
			$tabla = $this->consultaPersonaByCias($UnidadOrganizativa->getResponsable()->getCias(), $fecha);
			$sub_data["text"] = $UnidadOrganizativa->getResponsable()->getCias() . " " . $tabla["nombre"];
			$sub_data["tipo"] = "responsable";
			$sub_data["cias"] = $UnidadOrganizativa->getResponsable()->getCias();
			$sub_data["icon"] = "glyphicon glyphicon-registration-mark";
			$sub_data["color"] = "#ff0000";
			$data[] = $sub_data;
		}

		$PlazaAll = $em->getRepository("CostesBundle:Plaza")->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa ')
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);
		foreach ($PlazaAll as $Plaza) {
			if ($Plaza["cias"] != $UnidadOrganizativa->getResponsable()->getCias()) {
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
			->orderBy('u.codigo', 'asc')
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
		$fecha = date('Y').'-'.date('m').'-'.date('d');

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
	 * @param $id
	 * @return array
	 */

	public function verDependencia($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);
		$data = [];
		$fecha = date('Y').'-'.date('m').'-'.date('d');
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
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;

		return $TempAltas[0];

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
					return $this->redirectToRoute("queryEstructura");
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

	public function imprimirEstructura($estructura) {

	}
}
