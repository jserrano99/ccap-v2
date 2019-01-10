<?php

namespace CostesBundle\Controller;


use CostesBundle\Entity\UnidadOrganizativa;
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
	public function editAction(Request $request, $id)
	{
		$EM = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $EM->getRepository("CostesBundle:UnidadOrganizativa")->find($id);

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
			$dependiente = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($row["id"]);
			$PlazaAll = $em->getRepository("CostesBundle:Plaza")->createQueryBuilder('u')
				->where('u.unidadOrganizativa = :unidadOrganizativa ')
				->setParameter('unidadOrganizativa', $dependiente)
				->getQuery()->getResult(Query::HYDRATE_ARRAY);
			foreach ($PlazaAll as $Plaza) {
				$sub_data["text"] = $Plaza["cias"];
				$sub_data["tipo"] = "cias";
				$sub_data["icon"] = "glyphicon glyphicon-user";
				$data[] = $sub_data;
			}
			$sub_data["tipo"] = "";
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
		$dependiente = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);


		$UnidadOrganizativaAll = $em->getRepository("CostesBundle:UnidadOrganizativa")->createQueryBuilder('u')
			->where('u.dependencia = :dependencia ')
			->setParameter('dependencia', $dependiente)
			->orderBy('u.codigo', 'asc')
			->getQuery()->getResult(Query::HYDRATE_ARRAY);
		$data = [];
		$PlazaAll = $em->getRepository("CostesBundle:Plaza")->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa ')
			->setParameter('unidadOrganizativa', $dependiente)
			->getQuery()->getResult(Query::HYDRATE_ARRAY);
		foreach ($PlazaAll as $Plaza) {
			$sub_data["text"] = $Plaza["cias"];
			$sub_data["tipo"] = "cias";
			$sub_data["icon"] = "glyphicon glyphicon-user";
			$data[] = $sub_data;
		}

		foreach ($UnidadOrganizativaAll as $row) {
			$sub_data["unidad"] = $row["id"];
			$sub_data["tipo"] = "";
			$sub_data["name"] = $row["codigo"];
			$sub_data["text"] = $row["codigo"] . " " . $row["descripcion"];
			$sub_data["icon"] = "";
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
	 * @param $id
	 * @return array
	 */

	public function verDependencia($id)
	{
		$em = $this->getDoctrine()->getManager();
		$UnidadOrganizativa = $em->getRepository("CostesBundle:UnidadOrganizativa")->find($id);
		$data = [];
		if ($UnidadOrganizativa->getDependencia() !== null) {
			$sub_data["id"] = $UnidadOrganizativa->getDependencia()->getId();
			$sub_data["unidad"] = $UnidadOrganizativa->getDependencia()->getDescripcion();
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
		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/costes/consultaPersonaByCias.php " . $modo . " " . $cias. " ".$fecha;

		exec($php_script, $SALIDA, $resultado);

		$em = $this->getDoctrine()->getManager();

		$TempAltas = $em->getRepository("CostesBundle:TempAltas")->createQueryBuilder('u')
			->getQuery()->getResult(Query::HYDRATE_ARRAY);;

		$response = new Response();
		$response->setContent(json_encode($TempAltas));
		$response->headers->set("Content-type", "application/json");
		return $response;

	}
}
