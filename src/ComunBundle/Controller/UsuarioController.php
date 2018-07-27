<?php

namespace ComunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ComunBundle\Form\UsuarioType;
use ComunBundle\Entity\Usuario;
use ComunBundle\Entity\cambioPassword;
use ComunBundle\Form\cambioPasswordType;
use Symfony\Component\HttpFoundation\Session\Session;

class UsuarioController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(\ComunBundle\Datatables\UsuarioDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('/comun/usuario/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Usuario_repo = $EntityManager->getRepository("ComunBundle:Usuario");
        $Usuario = $Usuario_repo->find($id);

        $UsuarioForm = $this->createForm(UsuarioType::class, $Usuario);
        $UsuarioForm->handleRequest($request);

        if ($UsuarioForm->isSubmitted()) {
            $Usuario->setCodigo($UsuarioForm->get('codigo')->getData());
            $Usuario->setNombre($UsuarioForm->get('nombre')->getData());
            $Usuario->setEmail($UsuarioForm->get('email')->getData());
            $Usuario->setEstadoUsuario($UsuarioForm->get('estadoUsuario')->getData());
            $Usuario->setPerfil($UsuarioForm->get('perfil')->getData());

            $EntityManager->persist($Usuario);
            $flush = $EntityManager->flush();
            if ($flush == null) {
                $status = 'Usuario Modificado Correctamente';
            } else {
                $status = 'Error en Modificación';
            }
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("queryUsuario");
        }

        return $this->render("comun/usuario/update.html.twig", array(
                    "form" => $UsuarioForm->createView(),
                    "usuario" => $Usuario
        ));
    }

    public function addAction(Request $request) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Usuario_repo = $EntityManager->getRepository("ComunBundle:Usuario");

        $Usuario = new Usuario();
        $UsuarioForm = $this->createForm(UsuarioType::class, $Usuario);
        $UsuarioForm->handleRequest($request);


        if ($UsuarioForm->isSubmitted()) {
            $Usuario = $Usuario_repo->findOneBy(array("codigo" => $UsuarioForm->get('codigo')->getData()));
            if (count($Usuario) == 0) {
                $newUsuario = new Usuario();
                $newUsuario->setCodigo($UsuarioForm->get('codigo')->getData());
                $newUsuario->setNombre($UsuarioForm->get('nombre')->getData());
                $newUsuario->setEmail($UsuarioForm->get('email')->getData());
                $newUsuario->setEstadoUsuario($UsuarioForm->get('estadoUsuario')->getData());
                $newUsuario->setPerfil($UsuarioForm->get('perfil')->getData());

                $factory = $this->get("security.encoder_factory");
                $encoder = $factory->getEncoder($newUsuario);
                $password = $encoder->encodePassword('cambiala', $newUsuario->getSalt());
                $newUsuario->setPassword($password);

                $EntityManager->persist($newUsuario);
                $EntityManager->flush();
                $status = 'Usuario Creado Correctamente';
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUsuario");
            } else {
                $status = " CÓDIGO DE USUARIO YA EXISTENTE ";
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        return $this->render("comun/usuario/insert.html.twig", array(
                    "form" => $UsuarioForm->createView()
        ));
    }

    public function deleteAction($id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Usuario_repo = $EntityManager->getRepository("ComunBundle:Usuario");
        $Usuario = $Usuario_repo->find($id);

        $EntityManager->remove($Usuario);
        $EntityManager->flush();

        $status = " USUARIO ELIMINADO CORRECTAMENTE ";
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryUsuario");
    }

}
