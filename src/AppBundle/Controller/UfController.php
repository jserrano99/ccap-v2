<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Uf;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Datatables\UfDatatable;
use Symfony\Component\HttpFoundation\Response;

class UfController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function verUfAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Uf_repo = $entityManager->getRepository("AppBundle:Uf");
        $Uf = $Uf_repo->find($id);
        $params = array("uf" => $Uf);
        return $this->render("uf/verUf.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("AppBundle:Uf");
        $Uf = $Uf_repo->find($id);

        $form = $this->createForm(\AppBundle\Form\UfType::class, $Uf);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Uf);
                $EM->flush();
                $params = array("id" => $Uf->getId(),"actuacion" => "UPDATE");
                return $this->redirectToRoute("replicaUf", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA UNIDAD FUNCIONAL CON ESTE CÓDIGO: " . $Uf->getUf();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            }
        }

        $params = array("uf" => $Uf,
            "form" => $form->createView());
        return $this->render("uf/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $EM = $this->getDoctrine()->getManager(); 
        $Uf_repo = $EM->getRepository("AppBundle:Uf");
        $Uf = $Uf_repo->find($id);
        $Uf->setEnUso('N');

        $EM->persist($Uf);
        $EM->flush();
        $status = " Unidad Funcional quitada de uso Correctamente";
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryUf");
    }

    public function addAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("AppBundle:Uf");
        $Uf = new Uf();

        $form = $this->createForm(\AppBundle\Form\UfType::class, $Uf);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Uf);
                $EM->flush();
                $params = array("id" => $Uf->getId(),"actuacion" => "INSERT");
                return $this->redirectToRoute("replicaUf", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA UNIDAD FUNCIONAL CON ESTE CÓDIGO: " . $Uf->getUf();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryUf");
            }
        }

        $params = array("uf" => $Uf,
            "accion" => "CREACIÓN",
            "form" => $form->createView());
        return $this->render("uf/edit.html.twig", $params);
    }

    public function replicaAction($id,$actuacion) {

        $entityManager = $this->getDoctrine()->getManager();
        $Uf_repo = $entityManager->getRepository("AppBundle:Uf");
        $Uf = $Uf_repo->find($id);

        $resultado = $this->replicaUf($Uf,$actuacion);
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("uf/finProceso.html.twig", $params);
    }

    public function replicaUf($Uf, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/actualizacionUf.php ".$modo . " ".$Uf->getUf() . " " . $actuacion;
        
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        
        return $resultado;
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(UfDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('uf/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function ajaxCalculaCodigoAction($codigo){
        $edificio = (int)substr($codigo,2,2);
        $areaZona = substr($codigo, 2, 4);
        
        
        $entityManager = $this->getDoctrine()->getManager();
        $Equivalencia_repo = $entityManager->getRepository("AppBundle:Equivalencia");
        $EquivalenciaAll = $Equivalencia_repo->createQueryBuilder('u')
                ->where ('u.areaZona = :areaZona')
                ->setParameter ('areaZona',$areaZona)
                ->getQuery()->getResult();
        if ($EquivalenciaAll) {
            $Equivalencia = $EquivalenciaAll[0];
            $codigo12 = $Equivalencia->getCodigo();
        } else {
            $codigo12 = 'XX';
        }
        
        $Edificio_repo =$entityManager->getRepository("AppBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                   ->where ("u.codigo = :codigo")
                    ->setParameter("codigo", $edificio)
                    ->getQuery()->getResult();
        $Edificio = $EdificioAll[0];
        
        $codigoSaint["codigo"] = $codigo12.substr($codigo, 6, 4);
        $codigoSaint["edificio"] = $Edificio->getId();
        $response = new Response();
        $response->setContent(json_encode($codigoSaint));
        $response->headers->set("Content-type", "application/json");
        return $response;

    }
}
