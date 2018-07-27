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

        return $this->render('categ/query.html.twig', array(
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

        return $this->render('categ/query.eq.html.twig', array(
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
                    "actuacion" => "UPDATE");
                return $this->redirectToRoute("replicaCateg", $params);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryCateg");
            }
        }

        $params = array("categ" => $Categ,
            "form" => $form->createView());
        return $this->render("categ/edit.html.twig", $params);
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
                    "actuacion" => "INSERT");
                return $this->redirectToRoute("replicaCateg", $params);
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
            "form" => $form->createView());
        return $this->render("categ/add.html.twig", $params);
    }

    public function replicaAction($id, $actuacion) {

        $entityManager = $this->getDoctrine()->getManager();
        $Categ_repo = $entityManager->getRepository("MaestrosBundle:Categ");
        $Categ = $Categ_repo->find($id);

        $resultado = $this->replicaCateg($Categ, $actuacion);
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("categ/finProceso.html.twig", $params);
    }

    public function replicaCateg($Categ, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/actualizacionCateg.php " . $modo . " " . $Categ->getId() . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionCateg.php " . $modo . " " . $Categ->getId() . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;

        return $resultado;
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

}
