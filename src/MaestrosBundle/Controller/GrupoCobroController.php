<?php

namespace MaestrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use MaestrosBundle\Datatables\GrupoCobroDatatable;
use MaestrosBundle\Datatables\EqGrupoCobroDatatable;
use MaestrosBundle\Entity\GrupoCobro;
use MaestrosBundle\Form\GrupoCobroType;
use ComunBundle\Entity\SincroLog;


/**
 * Class GrupoCobroController
 * @package MaestrosBundle\Controller
 */
class GrupoCobroController extends Controller
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
	private $sesion;

	/**
	 * GrupoCobroController constructor.
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

		$datatable = $this->get('sg_datatables.factory')->create(GrupoCobroDatatable::class);
		$datatable->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatable);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$datatableQueryBuilder->buildQuery();

			return $responseService->getResponse();
		}

		return $this->render('maestros/grupoCobro/query.html.twig', ['datatable' => $datatable]);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function verEquiAction(Request $request, $id)
	{
		$isAjax = $request->isXmlHttpRequest();
		$em = $this->getDoctrine()->getManager();
		$GrupoCobro = $em->getRepository("MaestrosBundle:GrupoCobro")->find($id);

		$datatable = $this->get('sg_datatables.factory')->create(EqGrupoCobroDatatable::class);
		$datatable->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatable);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$qb = $datatableQueryBuilder->getQb();
			$qb->andWhere('grupoCobro = :grupoCobro');
			$qb->setParameter('grupoCobro', $GrupoCobro);

			return $responseService->getResponse();
		}

		return $this->render('maestros/grupoCobro/query.eq.html.twig', ['datatable' => $datatable]);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$GrupoCobro = $entityManager->getRepository("MaestrosBundle:GrupoCobro")->find($id);


		$form = $this->createForm(GrupoCobroType::class, $GrupoCobro);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($GrupoCobro);
			$entityManager->flush();
			$resultado = $this->sincroniza($GrupoCobro->getId(), "UPDATE");
			$params = ["error" => $resultado["error"],
				"log" => $resultado["log"]];
			return $this->render('finSincro.html.twig', $params);
		}

		$params = ["GrupoCobro" => $GrupoCobro,
			"form" => $form->createView(),
			"accion" => 'MODIFICACIÓN'];
		return $this->render("maestros/grupoCobro/edit.html.twig", $params);
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function deleteAction($id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$GrupoCobro = $entityManager->getRepository("MaestrosBundle:GrupoCobro")->find($id);
		$resultado = $this->sincroniza($GrupoCobro->getId(), "DELETE");
		$entityManager->remove($GrupoCobro);
		$entityManager->flush();
		$params = ["error" => $resultado["error"],
			"log" => $resultado["log"]];
		return $this->render('finSincro.html.twig', $params);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$GrupoCobro = new GrupoCobro();

		$form = $this->createForm(GrupoCobroType::class, $GrupoCobro);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$entityManager->persist($GrupoCobro);
				$entityManager->flush();
				$this->creaEquivalencia($GrupoCobro);
				$resultado = $this->sincroniza($GrupoCobro->getId(), "INSERT");
				$params = ["error" => $resultado["error"],
					"log" => $resultado["log"]];
				return $this->render('finSincro.html.twig', $params);
			} catch (UniqueConstraintViolationException $ex) {
				$status = "Error ya existe una forma de contratación con este codigo: " . $GrupoCobro->getCodigo();
				$this->sesion->getFlashBag()->add("status", $status);
			} catch (Doctrine\DBAL\DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
			}
		}

		$params = ["GrupoCobro" => $GrupoCobro,
			"form" => $form->createView(),
			"accion" => 'CREACIÓN'];
		return $this->render("maestros/grupoCobro/edit.html.twig", $params);
	}

	/**
	 * @param $eqgrupocobro_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function activarAction($eqgrupocobro_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqGrupoCobro = $em->getRepository("MaestrosBundle:EqGrupoCobro")->find($eqgrupocobro_id);
		$params = ["id" => $EqGrupoCobro->getGrupoCobro()->getId(),
			"actuacion" => 'ACTIVAR',
			"edificio" => $EqGrupoCobro->getEdificio()->getCodigo(),
			"eqgrupocobro_id" => $EqGrupoCobro->getId()];
		return $this->redirectToRoute("sincroGrupoCobro", $params);
	}

	/**
	 * @param $eqgrupocobro_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function desactivarAction($eqgrupocobro_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqGrupoCobro = $em->getRepository("MaestrosBundle:EqGrupoCobro")->find($eqgrupocobro_id);
		$params = ["id" => $EqGrupoCobro->getGrupoCobro()->getId(),
			"actuacion" => 'DESACTIVAR',
			"edificio" => $EqGrupoCobro->getEdificio()->getCodigo(),
			"eqgrupocobro_id" => $EqGrupoCobro->getId()];
		return $this->redirectToRoute("sincroGrupoCobro", $params);
	}

	/**
	 * @param $id
	 * @param $actuacion
	 * @param $eqgrupocobro_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sincroAction($id, $actuacion, $eqgrupocobro_id)
	{
		$em = $this->getDoctrine()->getManager();
		$GrupoCobro = $em->getRepository("MaestrosBundle:GrupoCobro")->find($id);
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

		$SincroLog = new SincroLog();
		$fechaProceso = new \DateTime();

		$SincroLog->setUsuario($Usuario);
		$SincroLog->setTabla("gums_grupoCobro");
		$SincroLog->setIdElemento($GrupoCobro->getId());
		$SincroLog->setFechaProceso($fechaProceso);
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);

		$GrupoCobro->setSincroLog($SincroLog);
		$em->persist($GrupoCobro);
		$em->flush();

		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/maestros/actualizacionGrupoCobro.php " . $modo . " " . $GrupoCobro->getId() . " " . $actuacion . " " . $eqgrupocobro_id;
		exec($php_script, $SALIDA, $resultado);

		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		}

		$ficheroLog = 'sincroGrupoCobro-' . $GrupoCobro->getCodigo() . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('gums_grupoCobro->codigo:' . $GrupoCobro->getCodigo());
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
		return $this->render("maestros/finSincro.html.twig", $params);
	}

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function crearAction($eqgrupocobro_id) {
		$em = $this->getDoctrine()->getManager();
		$EqGrupoCobro = $em->getRepository("MaestrosBundle:EqGrupoCobro")->find($eqgrupocobro_id);
		if ($EqGrupoCobro->getCodigoLoc() == 'XXX') {
			$status = "ERROR EN EL CODIGO NO PUEDE SER (XXX) ";
			$this->sesion->getFlashBag()->add("status", $status);
			$params = ["id" => $EqGrupoCobro->getGrupoCobro()->getId()];
			return $this->redirectToRoute("equiGrupoCobro", $params);
		}
		$params = ["id" => $EqGrupoCobro->getGrupoCobro()->getId(),
			"actuacion" => 'CREAR',
			"eqgrupocobro_id" => $EqGrupoCobro->getId()];
		return $this->redirectToRoute("sincroGrupoCobro", $params);
	}

}