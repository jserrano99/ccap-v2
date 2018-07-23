<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseDatosController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\AppBundle\Datatables\BaseDatosDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('basedatos/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $BaseDatos_repo = $entityManager->getRepository("AppBundle:BaseDatos");
        $BaseDatos = $BaseDatos_repo->find($id);

        $form = $this->createForm(\AppBundle\Form\BaseDatosType::class, $BaseDatos);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($BaseDatos);
            $entityManager->flush();
            $status = "Base de Datos Modificada Correctamente";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("queryBaseDatos");
        }

        $params = array("BaseDatos" => $BaseDatos,
            "form" => $form->createView());
        return $this->render("basedatos/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $BaseDatos_repo = $entityManager->getRepository("AppBundle:BaseDatos");
        $BaseDatos = new \AppBundle\Entity\BaseDatos();

        $form = $this->createForm(\AppBundle\Form\BaseDatosType::class, $BaseDatos);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($BaseDatos);
            $entityManager->flush();
            $status = "Base de Datos Creada Correctamente";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("queryBaseDatos");
        }

        $params = array("BaseDatos" => $BaseDatos,
            "form" => $form->createView());
        return $this->render("basedatos/add.html.twig", $params);
    }

}
