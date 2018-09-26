<?php

namespace MaestrosBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use UniqueConstraintViolationException;

class CategController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\CategDatatable::class);
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

    public function queryEqCategAction(Request $request, $categ_id) {
        $em = $this->getDoctrine()->getManager();
        $Categ_repo = $em->getRepository("MaestrosBundle:Categ");
        $Categ = $Categ_repo->find($categ_id);

        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqCategDatatable::class);
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

        return $this->render('maestros/categ/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Categ_repo = $entityManager->getRepository("MaestrosBundle:Categ");
        $Categ = $Categ_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\CategType::class, $Categ);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Categ);
                $entityManager->flush();
                $params = array("id" => $Categ->getId(),
                    "actuacion" => "UPDATE",
                    "eqcateg_id" => "TT");
                return $this->redirectToRoute("sincroCateg", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCateg");
            }
        }

        $params = array("categ" => $Categ,
            "accion" => "MODIFICACIÓN",
            "form" => $form->createView());
        return $this->render("maestros/categ/edit.html.twig", $params);
    }

    public function crearEquivalencias($Categ) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->querySoloAreas();
        foreach ($EdificioAll as $Edificio) {
            $EqCateg = new \MaestrosBundle\Entity\EqCateg();
            $EqCateg->setCateg($Categ);
            $EqCateg->setEdificio($Edificio);
            $EqCateg->setCodigoLoc('XXXX');
            $EqCateg->setEnUso('X');
            $entityManager->persist($EqCateg);
            $entityManager->flush();
        }
        return true;
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $Categ = new \MaestrosBundle\Entity\Categ();

        $form = $this->createForm(\MaestrosBundle\Form\CategType::class, $Categ);
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

        $params = array("categ" => $Categ,
            "accion" => "CREACIÓN",
            "form" => $form->createView());
        return $this->render("maestros/categ/edit.html.twig", $params);
    }

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

    public function sincroAction($id, $actuacion, $eqcateg_id) {
        $em = $this->getDoctrine()->getManager();
        $Categ = $em->getRepository("MaestrosBundle:Categ")->find($id);
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
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
        $php_script = "php " . $root . "/scripts/maestros/actualizacionCateg.php " . $modo . " " . $Categ->getId() . " " . $actuacion . " " . $eqcateg_id;
        $mensaje = exec($php_script, $SALIDA, $resultado);

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

        $params = array("SincroLog" => $SincroLog,
            "resultado" => $resultado);
        return $this->render("maestros/finSincro.html.twig", $params);
    }

    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $CatFp = $em->getRepository("MaestrosBundle:Categ")->find($id);
        $params = array("id" => $Categ->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

    public function activarAction($eqcateg_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCateg = $em->getRepository("MaestrosBundle:EqCateg")->find($eqcateg_id);
        $params = array("id" => $EqCateg->getCateg()->getId(),
            "actuacion" => 'ACTIVAR',
            "edificio" => $EqCateg->getEdificio()->getCodigo(),
            "eqcateg_id" => $EqCateg->getId());
        return $this->redirectToRoute("sincroCateg", $params);
    }

    public function desactivarAction($eqcateg_id) {
        $em = $this->getDoctrine()->getManager();
        $EqCateg = $em->getRepository("MaestrosBundle:EqCateg")->find($eqcateg_id);
        $params = array("id" => $EqCateg->getCateg()->getId(),
            "actuacion" => 'DESACTIVAR',
            "edificio" => $EqCateg->getEdificio()->getCodigo(),
            "eqcateg_id" => $EqCateg->getId());
        return $this->redirectToRoute("sincroCateg", $params);
    }

    public function crearAction($id) {
        $em = $this->getDoctrine()->getManager();
        $EqCateg = $em->getRepository("MaestrosBundle:EqCateg")->find($id);
        if ($EqCateg->getCodigoLoc() == 'XXXX') {
            $status = "ERROR EN EL CODIGO NO PUEDE SER (XXXX) ";
            $this->sesion->getFlashBag()->add("status", $status);
            $params = array("cateq_id" => $EqCateg->getCateg()->getId());
            return $this->redirectToRoute("queryEqCateg", $params);
        }
        $params = array("id" => $EqCateg->getCateg()->getId(),
            "actuacion" => 'CREAR',
            "eqcateg_id" => $EqCateg->getId());
        return $this->redirectToRoute("sincroCateg", $params);
    }

}
