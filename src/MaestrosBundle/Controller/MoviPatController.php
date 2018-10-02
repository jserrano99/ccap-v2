<?php

namespace MaestrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\DBALException;

class MoviPatController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\MoviPatDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/movipat/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verEquiAction(Request $request, $id) {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $MoviPat_repo = $em->getRepository("MaestrosBundle:MoviPat");
        $MoviPat = $MoviPat_repo->find($id);

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqMoviPatDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('moviPat = :moviPat');
            $qb->setParameter('moviPat', $MoviPat);

            return $responseService->getResponse();
        }

        return $this->render('maestros/movipat/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $MoviPat_repo = $entityManager->getRepository("MaestrosBundle:MoviPat");
        $MoviPat = $MoviPat_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\MoviPatType::class, $MoviPat);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($MoviPat);
            $entityManager->flush();
            $resultado = $this->sincroniza($MoviPat->getId(), "UPDATE");
            $params = array("error" => $resultado["error"],
                "log" => $resultado["log"]);
            return $this->render('finSincro.html.twig', $params);
        }

        $params = array("MoviPat" => $MoviPat,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/movipat/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $MoviPat_repo = $entityManager->getRepository("MaestrosBundle:MoviPat");
        $MoviPat = $MoviPat_repo->find($id);
        $resultado = $this->sincroniza($MoviPat->getId(), "DELETE");
        $entityManager->remove($MoviPat);
        $entityManager->flush();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('finSincro.html.twig', $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $MoviPat_repo = $entityManager->getRepository("MaestrosBundle:MoviPat");
        $MoviPat = new \MaestrosBundle\Entity\MoviPat();

        $form = $this->createForm(\MaestrosBundle\Form\MoviPatType::class, $MoviPat);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($MoviPat);
                $entityManager->flush();
                $this->creaEquivalencia($MoviPat);
                $resultado = $this->sincroniza($MoviPat->getId(), "INSERT");
                $params = array("error" => $resultado["error"],
                    "log" => $resultado["log"]);
                return $this->render('finSincro.html.twig', $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = "Error ya existe una cuenta cotización con este codigo: " . $MoviPat->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("MoviPat" => $MoviPat,
            "form" => $form->createView(),
            "accion" => 'CREACIÓN');
        return $this->render("maestros/movipat/edit.html.twig", $params);
    }

    public function creaEquivalencia($MoviPat) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                        ->where("u.area = 'S' ")
                        ->getQuery()->getResult();
        foreach ($EdificioAll as $Edificio) {
            $EqMoviPat = new \MaestrosBundle\Entity\EqMoviPat();
            $EqMoviPat->setEdificio($Edificio);
            $EqMoviPat->setMoviPat($MoviPat);
            $EqMoviPat->setCodigoLoc($MoviPat->getCodigo());
            $entityManager->persist($EqMoviPat);
            $entityManager->flush();
        }

        return true;
    }
    
}
