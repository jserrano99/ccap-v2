<?php

namespace CostesBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AsignacionDirectaController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction() {
        $entityManager = $this->getDoctrine()->getManager();
        $AsignacionDirecta_repo = $entityManager->getRepository("CostesBundle:AsignacionDirecta");
        $AsignacionDirectaAll = $AsignacionDirecta_repo->findAll();
        $params = array("AsignacionDirectaAll" => $AsignacionDirectaAll);
        return $this->render("costes/asignacionDirecta/query.html.twig", $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $AsignacionDirecta = new \CostesBundle\Entity\AsignacionDirecta();
        $form = $this->createForm(\CostesBundle\Form\AsignacionDirectaType::class, $AsignacionDirecta);
        $form->handleRequest($request);
        $Ceco_repo = $entityManager->getRepository("CostesBundle:Ceco");
        if ($form->isSubmitted()) {
            if ($form->get('cecoInf')->getData() != null) {
                $Ceco = $Ceco_repo->createQueryBuilder('u')
                                ->where('u.codigo = :codigo')
                                ->setParameter('codigo', $form->get('cecoInf')->getData())
                                ->getQuery()->getResult();
                if (!$Ceco) {
                    $status = " Error no existe ceco : " . $form->get('cecoInf')->getData() . " no creado ";
                    $this->sesion->getFlashBag()->add("status", $status);
                    $params = array("accion" => "NUEVA",
                        "form" => $form->createView());
                    return $this->render("costes/asignacionDirecta/edit.html.twig", $params);
                } else {
                    $AsignacionDirecta->setCeco($Ceco[0]);
                }
            } else {
                $AsignacionDirecta->setCeco($form->get('ceco')->getData());
            }
            $AsignacionDirecta->setCodigoUf78($form->get('codigoUf78')->getData());
            $AsignacionDirecta->setDecripcion($form->get('descripcion')->getData());
            try {
                $entityManager->persist($AsignacionDirecta);
                $entityManager->flush();
            } catch (\Doctrine\DBAL\DBALException $ex) {
                $status = " Error DBAL " . $ex->getMessage() . " no creado ";
                $this->sesion->getFlashBag()->add("status", $status);
                $params = array("accion" => "NUEVA",
                    "form" => $form->createView());
                return $this->render("costes/asignacionDirecta/edit.html.twig", $params);
            }
            return $this->redirectToRoute("queryAsignacionDirecta");
        }

        $params = array("accion" => "NUEVA",
            "form" => $form->createView());
        return $this->render("costes/asignacionDirecta/edit.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Ceco_repo = $entityManager->getRepository("CostesBundle:Ceco");

        $AsignacionDirecta_repo = $entityManager->getRepository("CostesBundle:AsignacionDirecta");
        $AsignacionDirecta = $AsignacionDirecta_repo->find($id);

        $form = $this->createForm(\CostesBundle\Form\AsignacionDirectaType::class, $AsignacionDirecta);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->get('cecoInf')->getData() != null) {
                $Ceco = $Ceco_repo->createQueryBuilder('u')
                                ->where('u.codigo = :codigo')
                                ->setParameter('codigo', $form->get('cecoInf')->getData())
                                ->getQuery()->getResult();
                if (!$Ceco) {
                    $status = " Error no existe ceco : " . $form->get('cecoInf')->getData() . " no creado ";
                    $this->sesion->getFlashBag()->add("status", $status);
                } else {
                    $AsignacionDirecta->setCeco($Ceco);
                }
            } else {
                $AsignacionDirecta->setCeco($form->get('ceco')->getData());
            }
            $AsignacionDirecta->setCodigoUf78($form->get('codigo78')->getData());
            $AsignacionDirecta->setDecripcion($form->get('descripcion')->getData());
            $entityManager->persist($AsignacionDirecta);
            $entityManager->flush();
            return $this->redirectToRoute("queryAsignacionDirecta");
        }

        $params = array("accion" => "MODIFICACIÓN",
            "form" => $form->createView());
        return $this->render("costes/asignacionDirecta/edit.html.twig", $params);
    }

}
