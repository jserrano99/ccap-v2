<?php

namespace MaestrosBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class TipoIltController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\TipoIltDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('maestros/tipoilt/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $TipoIlt_repo = $entityManager->getRepository("MaestrosBundle:TipoIlt");
        $TipoIlt = $TipoIlt_repo->find($id);

        $form = $this->createForm(\MaestrosBundle\Form\TipoIltType::class, $TipoIlt);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($TipoIlt);
            $entityManager->flush();
            $resultado = $this->sincroniza($TipoIlt->getId(), "UPDATE");
            $params = array("error" => $resultado["error"],
                "log" => $resultado["log"]);
            return $this->render('finSincro.html.twig', $params);
        }

        $params = array("TipoIlt" => $TipoIlt,
            "form" => $form->createView(),
            "accion" => 'MODIFICACIÓN');
        return $this->render("maestros/tipoilt/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $TipoIlt_repo = $entityManager->getRepository("MaestrosBundle:TipoIlt");
        $TipoIlt = $TipoIlt_repo->find($id);
        $resultado = $this->sincroniza($TipoIlt->getId(), "DELETE");
        $entityManager->remove($TipoIlt);
        $entityManager->flush();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('finSincro.html.twig', $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $TipoIlt_repo = $entityManager->getRepository("MaestrosBundle:TipoIlt");
        $TipoIlt = new \MaestrosBundle\Entity\TipoIlt();

        $form = $this->createForm(\MaestrosBundle\Form\TipoIltType::class, $TipoIlt);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($TipoIlt);
                $entityManager->flush();
                $resultado = $this->sincroniza($TipoIlt->getId(), "INSERT");
                $params = array("error" => $resultado["error"],
                    "log" => $resultado["log"]);
                return $this->render('finSincro.html.twig', $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = "Error ya existe un tipo de IT con este codigo: " . $TipoIlt->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("TipoIlt" => $TipoIlt,
            "form" => $form->createView(),
            "accion" => 'CREACIÓN');
        return $this->render("maestros/tipoilt/edit.html.twig", $params);
    }
    
    public function verEquiAction(Request $request, $id) {
        $isAjax = $request->isXmlHttpRequest();
        $em = $this->getDoctrine()->getManager();
        $TipoIlt_repo = $em->getRepository("MaestrosBundle:TipoIlt");
        $TipoIlt = $TipoIlt_repo->find($id);

        $datatable = $this->get('sg_datatables.factory')->create(\MaestrosBundle\Datatables\EqTipoIltDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $qb = $datatableQueryBuilder->getQb();
            $qb->andWhere('tipoIlt = :tipoIlt');
            $qb->setParameter('tipoIlt', $TipoIlt);

            return $responseService->getResponse();
        }

        return $this->render('maestros/tipoilt/query.eq.html.twig', array(
                    'datatable' => $datatable,
        ));
    }


}
