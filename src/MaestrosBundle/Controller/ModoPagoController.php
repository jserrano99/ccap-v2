<?php

namespace MaestrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ModoPagoController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\ModoPagoDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/modopago/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function verEquiAction(Request $request, $id) {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $ModoPago_repo = $em->getRepository("MaestrosBundle:ModoPago");
        $ModoPago = $ModoPago_repo->find($id);

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqModoPagoDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('modoPago = :modoPago');
            $qb->setParameter('modoPago', $ModoPago);

            return $responseService->getResponse();
        }

        return $this->render('maestros/modopago/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $ModoPago_repo = $entityManager->getRepository("MaestrosBundle:ModoPago");
        $ModoPago = $ModoPago_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\ModoPagoType::class, $ModoPago);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($ModoPago);
            $entityManager->flush();
            $status = "Modo Ocupación Modificado Correctamente";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("queryModoPago");
        }

        $params = array("ModoPago" => $ModoPago,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/modopago/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $ModoPago_repo = $entityManager->getRepository("MaestrosBundle:ModoPago");
        $ModoPago = $ModoPago_repo->find($id);
        $resultado = $this->sincroniza($ModoPago->getId(), "DELETE");
        $entityManager->remove($ModoPago);
        $entityManager->flush();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('finSincro.html.twig', $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $ModoPago = new \MaestrosBundle\Entity\ModoPago();

        $form = $this->createForm(\MaestrosBundle\Form\ModoPagoType::class, $ModoPago);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($ModoPago);
                $entityManager->flush();
                $this->creaEquivalencia($ModoPago);
                $resultado = $this->sincroniza($ModoPago->getId(), "INSERT");
                $params = array("error" => $resultado["error"],
                    "log" => $resultado["log"]);
                return $this->render('finSincro.html.twig', $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = "Error ya existe un modo ocupación con este codigo: " . $ModoPago->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("ModoPago" => $ModoPago,
            "form" => $form->createView(),
            "accion" => 'CREACIÓN');
        return $this->render("maestros/modopago/edit.html.twig", $params);
    }

    public function creaEquivalencia($ModoPago) {
        $entityManager = $this->getDoctrine()->getManager();
        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                ->where("u.area = 'S' ")
                ->getQuery()->getResult();
        foreach ($EdificioAll as $Edificio) {
            $EqModoPago = new \MaestrosBundle\Entity\EqModoPago();
            $EqModoPago->setEdificio($Edificio);
            $EqModoPago->setModoPago($ModoPago);
            $EqModoPago->setCodigoLoc($ModoPago->getCodigo());
            $entityManager->persist($EqModoPago);
            $entityManager->flush();
        }

        return true;
    }

    
}
