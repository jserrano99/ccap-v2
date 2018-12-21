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

		$params = ["unidadOrganizativa" => $UnidadOrganizativa, "accion" => "MODIFICACION", "form" => $form->createView()];
		return $this->render("costes/unidad/edit.html.twig", $params);
	}

	/**
	 * @param $id
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
			$sub_data["unidad"] = $row["id"];
			$sub_data["name"] = $row["codigo"];
			$sub_data["text"] = $row["codigo"]." ".$row["descripcion"];
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
		foreach ($UnidadOrganizativaAll as $row) {
			$sub_data["unidad"] = $row["id"];
			$sub_data["name"] = $row["codigo"];
			$sub_data["text"] = $row["codigo"]." ".$row["descripcion"];
			$sub_data["nodes"] = $this->verDependientes($row["id"]);
			$data[] = $sub_data;
		}

		return $data;

	}

}
