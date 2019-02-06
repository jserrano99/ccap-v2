<?php

namespace MaestrosBundle\Controller;

use ComunBundle\Entity\SincroLog;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use MaestrosBundle\Datatables\CatFpDatatable;
use MaestrosBundle\Datatables\EqCatFpDatatable;
use MaestrosBundle\Entity\CatFp;
use MaestrosBundle\Entity\EqCatFp;
use MaestrosBundle\Form\CatFpType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CatFpController
 *
 * @package MaestrosBundle\Controller
 */
class CatFpController extends Controller
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
	private $sesion;

	/**
	 * CatFpController constructor.
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

		$datatable = $this->get('sg_datatables.factory')->create(CatFpDatatable::class);
		$datatable->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatable);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$datatableQueryBuilder->buildQuery();
			return $responseService->getResponse();
		}


		return $this->render('maestros/catfp/query.html.twig', [
			'datatable' => $datatable,
		]);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param  int                                      $catfp_id
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function queryEqCatFpAction(Request $request, $catfp_id)
	{
		$em = $this->getDoctrine()->getManager();
		$CatFp_repo = $em->getRepository("MaestrosBundle:CatFp");
		$CatFp = $CatFp_repo->find($catfp_id);

		$isAjax = $request->isXmlHttpRequest();

		$datatable = $this->get('sg_datatables.factory')->create(EqCatFpDatatable::class);
		$datatable->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatable);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$qb = $datatableQueryBuilder->getQb();
			$qb->andWhere('catfp = :catfp');
			$qb->setParameter('catfp', $CatFp);

			return $responseService->getResponse();
		}

		return $this->render('maestros/catfp/query.eq.html.twig', [
			'datatable' => $datatable,
		]);
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param  int                                      $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, $id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$CatFp_repo = $entityManager->getRepository("MaestrosBundle:CatFp");
		$CatFp = $CatFp_repo->find($id);

		$form = $this->createForm(CatFpType::class, $CatFp);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				$entityManager->persist($CatFp);
				$entityManager->flush();
				$params = ["id" => $CatFp->getId(),
					"actuacion" => "UPDATE",
					"edificio" => 'TODOS',
					'eqcatfp_id' => "TT"];
				return $this->redirectToRoute("sincroCatFp", $params);
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
				return $this->redirectToRoute("queryCatFp");
			}
		}

		$params = ["catfp" => $CatFp,
			"accion" => "MODIFICACIÓN",
			"form" => $form->createView()];
		return $this->render("maestros/catfp/edit.html.twig", $params);
	}

	/**
	 * @param $CatFp
	 * @return bool
	 */
	public function crearEquivalencias($CatFp)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
		$EdificioAll = $Edificio_repo->querySoloAreas();
		foreach ($EdificioAll as $Edificio) {
			$EqCatFp = new EqCatFp();
			$EqCatFp->setCatFp($CatFp);
			$EqCatFp->setEdificio($Edificio);
			$EqCatFp->setCodigoLoc($CatFp->getCodigo());
			$EqCatFp->setEnuso('X');
			$entityManager->persist($EqCatFp);
			$entityManager->flush();
		}
		return true;
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$CatFp = new CatFp();

		$form = $this->createForm(CatFpType::class, $CatFp);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			try {
				$entityManager->persist($CatFp);
				$entityManager->flush();
				$this->crearEquivalencias($CatFp);
				$params = ["id" => $CatFp->getId(),
					"actuacion" => "INSERT",
					'eqcatfp_id' => 9];
				return $this->redirectToRoute("sincroCatFp", $params);
			} catch (UniqueConstraintViolationException $ex) {
				$status = " YA EXISTE UNA CATEGORIA PROFESIONAL ESTE CÓDIGO: " . $CatFp->getCodigo();
				$this->sesion->getFlashBag()->add("status", $status);
				return $this->redirectToRoute("queryCatFp");
			} catch (DBALException $ex) {
				$status = "ERROR GENERAL=" . $ex->getMessage();
				$this->sesion->getFlashBag()->add("status", $status);
				return $this->redirectToRoute("queryCatFp");
			}
		}

		$params = ["catfp" => $CatFp,
			"accion" => "CREACIÓN",
			"form" => $form->createView()];
		return $this->render("maestros/catfp/edit.html.twig", $params);
	}

	/**
	 * @param int $eqcatfp_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function activarAction($eqcatfp_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqCatFp = $em->getRepository("MaestrosBundle:EqCatFp")->find($eqcatfp_id);
		$params = ["id" => $EqCatFp->getCatFp()->getId(),
			"actuacion" => 'ACTIVAR',
			"eqcatfp_id" => $eqcatfp_id];
		return $this->redirectToRoute("sincroCatFp", $params);
	}

	/**
	 * @param int $eqcatfp_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function desactivarAction($eqcatfp_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqCatFp = $em->getRepository("MaestrosBundle:EqCatFp")->find($eqcatfp_id);
		$params = ["id" => $EqCatFp->getCatFp()->getId(),
			"actuacion" => 'DESACTIVAR',
			"eqcatfp_id" => $eqcatfp_id];
		return $this->redirectToRoute("sincroCatFp", $params);
	}

	/**
	 * @param int $eqcatfp_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function crearAction($eqcatfp_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqCatFp = $em->getRepository("MaestrosBundle:EqCatFp")->find($eqcatfp_id);
		if ($EqCatFp->getCodigoLoc() == 'XXXX') {
			$status = "ERROR EN EL CODIGO NO PUEDE SER (XXXX) ";
			$this->sesion->getFlashBag()->add("status", $status);
			$params = ["catfp_id" => $EqCatFp->getCatFp()->getId()];
			return $this->redirectToRoute("queryEqCatFp", $params);
		}
		$params = ["id" => $EqCatFp->getCatFp()->getId(),
			"actuacion" => 'CREAR',
			"eqcatfp_id" => $EqCatFp->getId()];
		return $this->redirectToRoute("sincroCatFp", $params);
	}

	/**
	 * @param int    $id
	 * @param string $actuacion
	 * @param int    $eqcatfp_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function sincroAction($id, $actuacion, $eqcatfp_id)
	{
		$em = $this->getDoctrine()->getManager();
		$CatFp = $em->getRepository("MaestrosBundle:CatFp")->find($id);
		$usuario_id = $this->sesion->get('usuario_id');
		$Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
		$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

		$SincroLog = new SincroLog();
		$fechaProceso = new DateTime();

		$SincroLog->setUsuario($Usuario);
		$SincroLog->setTabla("gums_catfp");
		$SincroLog->setIdElemento($CatFp->getId());
		$SincroLog->setFechaProceso($fechaProceso);
		$SincroLog->setEstado($Estado);
		$em->persist($SincroLog);

		$CatFp->setSincroLog($SincroLog);
		$em->persist($CatFp);
		$em->flush();

		$root = $this->get('kernel')->getRootDir();
		$modo = $this->getParameter('modo');
		$php = $this->getParameter('php');
		$php_script = $php . " " . $root . "/scripts/maestros/actualizacionCatFp.php " . $modo . " " . $CatFp->getId() . " " . $actuacion . " " . $eqcatfp_id;
		exec($php_script, $SALIDA, $resultado);

		if ($resultado == 0) {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
		} else {
			$Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
		}

		$ficheroLog = 'sincroCatFp-' . $CatFp->getCodigo() . '.log';
		$ServicioLog = $this->get('app.escribelog');
		$ServicioLog->setLogger('gums_catfp->codigo:' . $CatFp->getCodigo());
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
	 * @param int $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function descargaLogAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$CatFp = $em->getRepository("MaestrosBundle:CatFp")->find($id);
		$params = ["id" => $CatFp->getSincroLog()->getId()];
		return $this->redirectToRoute("descargaSincroLog", $params);
	}
}
