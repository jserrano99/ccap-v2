<?php

namespace MaestrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use UniqueConstraintViolationException;
use ComunBundle\Entity\SincroLog;
use MaestrosBundle\Form\CategType;
use MaestrosBundle\Datatables\EqCategDatatable;
use MaestrosBundle\Datatables\CategDatatable;
use Doctrine\DBAL\DBALException;

/**
 * Class CategController
 * @package MaestrosBundle\Controller
 */
class CategController extends Controller {

    private $sesion;

    /**
     * CategController constructor.
     */
    public function __construct() {
        $this->sesion = new Session();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Exception
     */
    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(CategDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/categ/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    /**
     * @param Request $request
     * @param $categ_id
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     * @throws \Exception
     */
    public function queryEqCategAction(Request $request, $categ_id) {
        $em = $this->getDoctrine()->getManager();
        $Categ_repo = $em->getRepository("MaestrosBundle:Categ");
        $Categ = $Categ_repo->find($categ_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(EqCategDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('categ = :categ');
            $qb->setParameter('categ', $Categ);

            return $responseService->getResponse();
        }

        $params = ['datatable' => $datatable];
        return $this->render('maestros/categ/query.eq.html.twig', $params);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Categ_repo = $entityManager->getRepository("MaestrosBundle:Categ");
        $Categ = $Categ_repo->find($id);

        $form = $this->createForm(CategType::class, $Categ);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Categ);
                $entityManager->flush();
                $params = ["id" => $Categ->getId(),
                    "actuacion" => "UPDATE",
                    "eqcateg_id" => "TT"];
                return $this->redirectToRoute("sincroCateg", $params);
            } catch (DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCateg");
            }
        }

        $params = ["categ" => $Categ,
            "accion" => "MODIFICACIÓN",
            "form" => $form->createView()];
        return $this->render("maestros/categ/edit.html.twig", $params);
    }

    
    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Categ = new \MaestrosBundle\Entity\Categ();

        $form = $this->createForm(CategType::class, $Categ);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Categ);
                $entityManager->flush();
                $this->crearEquivalencias($Categ);
                $params = array("id" => $Categ->getId(),
                    "actuacion" => "INSERT",
                    "eqcateg_id" => "NULL");
                return $this->redirectToRoute("sincroCateg", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA CATEGORIA PROFESIONAL ESTE CÓDIGO: " . $Categ->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCateg");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCateg");
            }
        }

        $params = ["categ" => $Categ,
            "accion" => "CREACIÓN",
            "form" => $form->createView()];
        return $this->render("maestros/categ/edit.html.twig", $params);
    }

    /**
     * @param $Categ
     * @return bool
     */
    public function crearEquivalencias($Categ) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->querySoloAreas();
        foreach ($EdificioAll as $Edificio) {
            $EqCateg = new \MaestrosBundle\Entity\EqCateg();
            $EqCateg->setCateg($Categ);
            $EqCateg->setEdificio($Edificio);
            $EqCateg->setCodigoLoc('XXXX');
            $EqCateg->setEnuso('X');
            $entityManager->persist($EqCateg);
            $entityManager->flush();
        }
        return true;
    }

    /**
     * @param $catgen_id
     * @return Response
     */
    public function ajaxCalculaCodigoAction($catgen_id) {

        $em = $this->getDoctrine()->getManager();
        $CatGen_repo = $em->getRepository("MaestrosBundle:CatGen");
        $CatGen = $CatGen_repo->find($catgen_id);
        $Categ_repo = $em->getRepository("MaestrosBundle:Categ");
        $UltimaCateg = $Categ_repo->createQueryBuilder('u')
                        ->select('max(u.codigo) as codigo')
                        ->where('u.catGen = :catgen')
                        ->setParameter('catgen', $CatGen)
                        ->getQuery()->getResult();
        $ultimoCodigo = $UltimaCateg[0]["codigo"];
        if (substr($ultimoCodigo, 2, 2) == 99) {
            $codigo["codigo"] = $CatGen->getCodigo() . "ZZ";
        } else {
            $codigo["codigo"] = $CatGen->getCodigo() . sprintf('%02d', substr($ultimoCodigo, 2, 2) + 1);
        }

        $response = new Response();
        $response->setContent(json_encode($codigo));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

    /**
     * @param $id
     * @param $actuacion
     * @param $eqcateg_id
     * @return Response
     */
    public function sincroAction($id, $actuacion, $eqcateg_id) {
        $em = $this->getDoctrine()->getManager();
        $Categ = $em->getRepository("MaestrosBundle:Categ")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("gums_categ");
        $SincroLog->setIdElemento($Categ->getId());
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Categ->setSincroLog($SincroLog);
        $em->persist($Categ);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php = $this->getParameter('php');
        $php_script = $php." " . $root . "/scripts/maestros/actualizacionCateg.php " . $modo . " " . $Categ->getId() . " " . $actuacion . " " . $eqcateg_id;
        exec($php_script, $SALIDA, $resultado);

        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroCateg-' . $Categ->getCodigo() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('gums_categ->codigo:' . $Categ->getCodigo());
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
    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Categ = $em->getRepository("MaestrosBundle:Categ")->find($id);
        $params = ["id" => $Categ->getSincroLog()->getId()];
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

    /**
     * @param $eqcateg_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activarAction($eqcateg_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCateg = $em->getRepository("MaestrosBundle:EqCateg")->find($eqcateg_id);
	    $params = ["id" => $EqCateg->getCateg()->getId(),
            "actuacion" => 'ACTIVAR',
            "edificio" => $EqCateg->getEdificio()->getCodigo(),
            "eqcateg_id" => $EqCateg->getId()];
        return $this->redirectToRoute("sincroCateg", $params);
    }

    /**
     * @param $eqcateg_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function desactivarAction($eqcateg_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCateg = $em->getRepository("MaestrosBundle:EqCateg")->find($eqcateg_id);
        $params = ["id" => $EqCateg->getCateg()->getId(),
            "actuacion" => 'DESACTIVAR',
            "edificio" => $EqCateg->getEdificio()->getCodigo(),
            "eqcateg_id" => $EqCateg->getId()];
        return $this->redirectToRoute("sincroCateg", $params);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function crearAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqCateg = $em->getRepository("MaestrosBundle:EqCateg")->find($id);
        if ($EqCateg->getCodigoLoc() == 'XXXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = ["cateq_id" => $EqCateg->getCateg()->getId()];
            return $this->redirectToRoute("queryEqCateg", $params);
        }
        $params = ["id" => $EqCateg->getCateg()->getId(),
            "actuacion" => 'CREAR',
            "eqcateg_id" => $EqCateg->getId()];
        return $this->redirectToRoute("sincroCateg", $params);
    }

}
