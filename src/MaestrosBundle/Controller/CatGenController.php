<?php

namespace MaestrosBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use UniqueConstraintViolationException;

class CatGenController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\CatGenDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/catgen/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function queryEqCatGenAction(Request $request, $catgen_id) {
        $em = $this->getDoctrine()->getManager();
        $CatGen_repo = $em->getRepository("MaestrosBundle:CatGen");
        $CatGen = $CatGen_repo->find($catgen_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqCatGenDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('catgen = :catgen');
            $qb->setParameter('catgen', $CatGen);

            return $responseService->getResponse();
        }

        return $this->render('maestros/catgen/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $CatGen_repo = $entityManager->getRepository("MaestrosBundle:CatGen");
        $CatGen = $CatGen_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\CatGenType::class, $CatGen);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($CatGen);
                $entityManager->flush();
                $params = array("id" => $CatGen->getId(),
                    "actuacion" => "UPDATE",
                    "edificio" => 'TODOS',
                    "eqcatgen_id" => 'TT');
                return $this->redirectToRoute("sincroCatGen", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCatGen");
            }
        }

        $params = array("catgen" => $CatGen,
            "accion" => "MODIFICACIÓN",
            "form" => $form->createView());
        return $this->render("maestros/catgen/edit.html.twig", $params);
    }

    public function crearEquivalencias($CatGen) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->querySoloAreas();
        foreach ($EdificioAll as $Edificio) {
            $EqCatGen = new \MaestrosBundle\Entity\EqCatGen();
            $EqCatGen->setCatGen($CatGen);
            $EqCatGen->setEdificio($Edificio);
            $EqCatGen->setCodigoLoc($CatGen->getCodigo());
            $EqCatGen->setEnUso('X');
            $entityManager->persist($EqCatGen);
            $entityManager->flush();
        }
        return true;
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $CatGen = new \MaestrosBundle\Entity\CatGen();

        $form = $this->createForm(\MaestrosBundle\Form\CatGenType::class, $CatGen);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($CatGen);
                $entityManager->flush();
                $this->crearEquivalencias($CatGen);
                $params = array("id" => $CatGen->getId(),
                    "actuacion" => "INSERT",
                    "edificio" => 'TODOS',
                    "eqcatgen_id" => 'TT');
                return $this->redirectToRoute("sincroCatGen", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA CATEGORIA GENERAL ESTE CÓDIGO: " . $CatGen->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCatGen");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCatGen");
            }
        }

        $params = array("catgen" => $CatGen,
            "accion" => "CREACIÓN",
            "form" => $form->createView());
        return $this->render("maestros/catgen/edit.html.twig", $params);
    }

    public function activarAction($eqcatgen_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCatGen = $em->getRepository("MaestrosBundle:EqCatGen")->find($eqcatgen_id);
        $params = array("id" => $EqCatGen->getCatGen()->getId(),
            "actuacion" => 'ACTIVAR',
            "eqcatgen_id" => $EqCatGen->getId(),
            "edificio" => $EqCatGen->getEdificio()->getCodigo());
        return $this->redirectToRoute("sincroCatGen", $params);
    }

    public function desactivarAction($eqcatgen_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCatGen = $em->getRepository("MaestrosBundle:EqCatGen")->find($eqcatgen_id);
        $params = array("id" => $EqCatGen->getCatGen()->getId(),
            "actuacion" => 'DESACTIVAR',
            "eqcatgen_id" => $EqCatGen->getId(),
            "edificio" => $EqCatGen->getEdificio()->getCodigo());
        return $this->redirectToRoute("sincroCatGen", $params);
    }

    public function crearAction($eqcatgen_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCatGen = $em->getRepository("MaestrosBundle:EqCatGen")->find($eqcatgen_id);
        if ($EqCatGen->getCodigoLoc() == 'XXXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = array("catgen_id" => $EqCatGen->getCatGen()->getId());
            return $this->redirectToRoute("queryEqCatGen", $params);
        }
        $params = array("id" => $EqCatGen->getCatGen()->getId(),
            "actuacion" => 'CREAR',
            "edificio" => $EqCatGen->getEdificio()->getCodigo());
        return $this->redirectToRoute("sincroCatGen", $params);
    }

    public function sincroAction($id, $actuacion,$eqcatgen_id, $edificio) {
        $em = $this->getDoctrine()->getManager();
        $CatGen = $em->getRepository("MaestrosBundle:CatGen")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("gums_catgen");
        $SincroLog->setIdElemento($CatGen->getId());
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $CatGen->setSincroLog($SincroLog);
        $em->persist($CatGen);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/maestros/actualizacionCatGen.php " . $modo . " " . $CatGen->getId() . " " . $actuacion." " .$eqcatgen_id . " " . $edificio;
        $mensaje = exec($php_script, $SALIDA, $resultado);

        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroCatGen-'.$CatGen->getCodigo().'.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('gums_catgen->codigo:'.$CatGen->getCodigo());
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
        $CatGen = $em->getRepository("MaestrosBundle:CatGen")->find($id);
        $params = array("id" => $CatGen->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

}
