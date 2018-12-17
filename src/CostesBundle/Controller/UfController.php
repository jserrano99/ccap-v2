<?php

namespace CostesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use CostesBundle\Entity\Uf;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use CostesBundle\Datatables\UfDatatable;
use Symfony\Component\HttpFoundation\Response;
use CostesBundle\Datatables\EqUfDatatable;
use ComunBundle\Entity\SincroLog;
use DateTime;
use CostesBundle\Form\UfType;
use Doctrine\DBAL\DBALException;


/**
 * Class UfController
 *
 * @package CostesBundle\Controller
 */
class UfController extends Controller {
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
    private $sesion;

	/**
	 * UfController constructor.
	 */
    public function __construct() {
        $this->sesion = new Session();
    }

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function verUfAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Uf_repo = $entityManager->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($id);
        $params = ["uf" => $Uf];
        return $this->render("costes/uf/verUf.html.twig", $params);
    }

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param                                           $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function editAction(Request $request, $id) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($id);

        $form = $this->createForm(UfType::class, $Uf);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Uf);
                $EM->flush();
                $params = ["id" => $Uf->getId(), "actuacion" => "UPDATE"];
                return $this->redirectToRoute("sincroUf", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA UNIDAD FUNCIONAL CON ESTE CÓDIGO: " . $Uf->getUf();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            } catch (DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            }
        }

        $params = ["uf" => $Uf,
            "accion" => "MODIFICACIÓN",
            "form" => $form->createView()];
        return $this->render("costes/uf/edit.html.twig", $params);
    }

	/**
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
    public function deleteAction($id) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($id);
        $Uf->setEnuso('N');

        $EM->persist($Uf);
        $EM->flush();
        $status = " Unidad Funcional quitada de uso Correctamente";
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryUf");
    }

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
    public function addAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();
        $Uf = new Uf();

        $form = $this->createForm(UfType::class, $Uf);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Uf);
                $EM->flush();
                $params = ["id" => $Uf->getId(), "actuacion" => "INSERT"];
                return $this->redirectToRoute("sincroUf", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA UNIDAD FUNCIONAL CON ESTE CÓDIGO: " . $Uf->getUf();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            } catch (DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            }
        }

        $params = ["uf" => $Uf,
            "accion" => "CREACIÓN",
            "form" => $form->createView()];
        return $this->render("costes/uf/edit.html.twig", $params);
    }

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(UfDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('costes/uf/query.html.twig', [
                    'datatable' => $datatable,
        ]);
    }

	/**
	 * @param $codigo
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function ajaxCalculaCodigoAction($codigo) {
        $gerencia = (int) substr($codigo, 2, 2);
        $areaZona = substr($codigo, 2, 4);


        $entityManager = $this->getDoctrine()->getManager();
        $Equivalencia_repo = $entityManager->getRepository("CostesBundle:Equivalencia");
        $EquivalenciaAll = $Equivalencia_repo->createQueryBuilder('u')
                        ->where('u.areaZona = :areaZona')
                        ->setParameter('areaZona', $areaZona)
                        ->getQuery()->getResult();
        if ($EquivalenciaAll) {
            $Equivalencia = $EquivalenciaAll[0];
            $codigo12 = $Equivalencia->getCodigo();
        } else {
            $codigo12 = 'XX';
        }

        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                        ->where("u.gerencia = :gerencia")
                        ->setParameter("gerencia", $gerencia)
                        ->getQuery()->getResult();
        IF ($EdificioAll == null) {
            $codigoSaint["codigo"] = "ERROR-".$gerencia;
        } else {
            $Edificio = $EdificioAll[0];
            $codigoSaint["codigo"] = $codigo12 . substr($codigo, 6, 4);
            $codigoSaint["gerencia"] = $gerencia;
            $codigoSaint["edificio_id"] = $Edificio->getId();
        }
        $response = new Response();
        $response->setContent(json_encode($codigoSaint));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

	/**
	 * @param $id
	 * @param $actuacion
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
    public function sincroAction($id, $actuacion) {
        $em = $this->getDoctrine()->getManager();
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
        $Uf = $em->getRepository("CostesBundle:Uf")->find($id);

        $SincroLog = new SincroLog();
        $fechaProceso = new DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("ccap_uf");
        $SincroLog->setIdElemento($id);
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Uf->setSincroLog($SincroLog);
        $em->persist($Uf);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php = $this->getParameter('php');
        $php_script = $php." " . $root . "/scripts/costes/actualizacionUf.php " . $modo . "  " . $Uf->getId() . " " . $actuacion;

        exec($php_script, $SALIDA, $resultado);
        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroUf-' . $Uf->getUf() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ccap_uf->codigo:' . $Uf->getUf());
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
    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Uf = $em->getRepository("CostesBundle:Uf")->find($id);
        $params = ["id" => $Uf->getSincroLog()->getId()];
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param  int                      $uf_id
	 * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function queryEqUfAction(Request $request, $uf_id)
	{
		$em = $this->getDoctrine()->getManager();
		$Uf = $em->getRepository("CostesBundle:Uf")->find($uf_id);;

		$isAjax = $request->isXmlHttpRequest();

		$datatable = $this->get('sg_datatables.factory')->create(EqUfDatatable::class);
		$datatable->buildDatatable();

		if ($isAjax) {
			$responseService = $this->get('sg_datatables.response');
			$responseService->setDatatable($datatable);
			$datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
			$qb = $datatableQueryBuilder->getQb();
			$qb->andWhere('uf = :uf');
			$qb->setParameter('uf', $Uf);
			return $responseService->getResponse();
		}

		$params = ['datatable' => $datatable];
		return $this->render('costes/uf/query.eq.html.twig', $params);
	}

	/**
	 * @param $equf_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function activarAction($equf_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqUf = $em->getRepository("CostesBundle:EqUf")->find($equf_id);
		$params = ["id" => $EqUf->getUf()->getId(),
			"actuacion" => 'ACTIVAR',
			"edificio" => $EqUf->getEdificio()->getCodigo(),
			"equf_id" => $EqUf->getId()];
		return $this->redirectToRoute("sincroUf", $params);
	}

	/**
	 * @param $equf_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function desActivarAction($equf_id)
	{
		$em = $this->getDoctrine()->getManager();
		$EqUf = $em->getRepository("CostesBundle:EqUf")->find($equf_id);
		$params = ["id" => $EqUf->getUf()->getId(),
			"actuacion" => 'DESACTIVAR',
			"edificio" => $EqUf->getEdificio()->getCodigo(),
			"equf_id" => $EqUf->getId()];
		return $this->redirectToRoute("sincroUf", $params);
	}

}
