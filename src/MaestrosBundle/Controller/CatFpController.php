<?php

namespace MaestrosBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use UniqueConstraintViolationException;

class CatFpController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\CatFpDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/catfp/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function queryEqCatFpAction(Request $request, $catfp_id) {
        $em = $this->getDoctrine()->getManager();
        $CatFp_repo = $em->getRepository("MaestrosBundle:CatFp");
        $CatFp = $CatFp_repo->find($catfp_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqCatFpDatatable::class);
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

        return $this->render('maestros/catfp/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $CatFp_repo = $entityManager->getRepository("MaestrosBundle:CatFp");
        $CatFp = $CatFp_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\CatFpType::class, $CatFp);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($CatFp);
                $entityManager->flush();
                $params = array("id" => $CatFp->getId(),
                    "actuacion" => "UPDATE",
                    "edificio" => 'TODOS',
                    'eqcatfp_id' => "TT");
                return $this->redirectToRoute("sincroCatFp", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCatFp");
            }
        }

        $params = array("catfp" => $CatFp,
            "accion" => "MODIFICACIÓN",
            "form" => $form->createView());
        return $this->render("maestros/catfp/edit.html.twig", $params);
    }

    public function crearEquivalencias($CatFp) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->querySoloAreas();
        foreach ($EdificioAll as $Edificio) {
            $EqCatFp = new \MaestrosBundle\Entity\EqCatFp();
            $EqCatFp->setCatFp($CatFp);
            $EqCatFp->setEdificio($Edificio);
            $EqCatFp->setCodigoLoc($CatFp->getCodigo());
            $EqCatFp->setEnuso('X');
            $entityManager->persist($EqCatFp);
            $entityManager->flush();
        }
        return true;
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $CatFp = new \MaestrosBundle\Entity\CatFp();

        $form = $this->createForm(\MaestrosBundle\Form\CatFpType::class, $CatFp);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($CatFp);
                $entityManager->flush();
                $this->crearEquivalencias($CatFp);
                $params = array("id" => $CatFp->getId(),
                    "actuacion" => "INSERT",
                    "edificio" => 'TODOS',
                    'eqcatfp_id' => "TT");
                return $this->redirectToRoute("sincroCatFp", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA CATEGORIA PROFESIONAL ESTE CÓDIGO: " . $CatFp->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCatFp");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCatFp");
            }
        }

        $params = array("catfp" => $CatFp,
            "accion" => "CREACIÓN",
            "form" => $form->createView());
        return $this->render("maestros/catfp/edit.html.twig", $params);
    }

    public function activarAction($eqcatfp_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCatFp = $em->getRepository("MaestrosBundle:EqCatFp")->find($eqcatfp_id);
        $params = array("id" => $EqCatFp->getCatFp()->getId(),
            "actuacion" => 'ACTIVAR',
            "edificio" => $EqCatFp->getEdificio()->getCodigo());
        return $this->redirectToRoute("sincroCatFp", $params);
    }

    public function desactivarAction($eqcatfp_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCatFp = $em->getRepository("MaestrosBundle:EqCatFp")->find($eqcatfp_id);
        $params = array("id" => $EqCatFp->getCatFp()->getId(),
            "actuacion" => 'DESACTIVAR',
            "edificio" => $EqCatFp->getEdificio()->getCodigo());
        return $this->redirectToRoute("sincroCatFp", $params);
    }

    public function crearAction($eqcatfp_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCatFp = $em->getRepository("MaestrosBundle:EqCatFp")->find($eqcatfp_id);
        if ($EqCatFp->getCodigoLoc() == 'XXXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = array("catfp_id" => $EqCatFp->getCatFp()->getId());
            return $this->redirectToRoute("queryEqCatFp", $params);
        }
        $params = array("id" => $EqCatFp->getCatFp()->getId(),
            "actuacion" => 'CREAR',
            "edificio" => $EqCatFp->getEdificio()->getCodigo());
        return $this->redirectToRoute("sincroCatFp", $params);
    }

    public function sincroAction($id, $actuacion, $edificio) {
        $em = $this->getDoctrine()->getManager();
        $CatFp = $em->getRepository("MaestrosBundle:CatFp")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

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
        $php_script = "php " . $root . "/scripts/maestros/actualizacionCatFp.php " . $modo . " " . $CatFp->getId() . " " . $actuacion . " " . $edificio;
        $mensaje = exec($php_script, $SALIDA, $resultado);

        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroCatFp-'.$CatFp->getCodigo().'.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('gums_catfp->codigo:'.$CatFp->getCodigo());
        foreach ($SALIDA as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog($ficheroLog);
        }
        $SincroLog->setScript($php_script);
        $SincroLog->setFicheroLog($ServicioLog->getFilename());
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);
        $em->flush();

        $params = array("SincroLog" => $SincroLog,
            "resultado" => $resultado);
        return $this->render("maestros/finSincro.html.twig", $params);
    }

    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $CatFp = $em->getRepository("MaestrosBundle:CatFp")->find($id);
        $params = array("id" => $CatFp->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

}
