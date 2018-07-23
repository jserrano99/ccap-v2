<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Pa;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Datatables\PaDatatable;

use Symfony\Component\HttpFoundation\Response;

class PaController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function verPaAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Pa_repo = $entityManager->getRepository("AppBundle:Pa");
        $Pa = $Pa_repo->find($id);
        $params = array("pa" => $Pa);
        return $this->render("pa/verPa.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $EM = $this->getDoctrine()->getManager();
        $Pa_repo = $EM->getRepository("AppBundle:Pa");
        $Pa = $Pa_repo->find($id);

        $form = $this->createForm(\AppBundle\Form\PaType::class, $Pa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Pa);
                $EM->flush();
                $params = array("id" => $Pa->getId(),"actuacion" => "UPDATE");
                return $this->redirectToRoute("replicaPa", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA PUNTO ASISTENCIAL CON ESTE CÓDIGO: " . $Pa->getPa();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPa");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPa");
            }
        }

        $params = array("pa" => $Pa,
            "form" => $form->createView());
        return $this->render("pa/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $EM = $this->getDoctrine()->getManager(); 
        $Pa_repo = $EM->getRepository("AppBundle:Pa");
        $Pa = $Pa_repo->find($id);
        $Pa->setEnUso('N');

        $EM->persist($Pa);
        $EM->flush();
        $status = " Unidad Funcional quitada de uso Correctamente";
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryPa");
    }

    public function addAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();
        $Pa_repo = $EM->getRepository("AppBundle:Pa");
        $Pa = new Pa();

        $form = $this->createForm(\AppBundle\Form\PaType::class, $Pa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $Pa->setPa(substr($Pa->getOficial(), 4, 6));
            try {
                $EM->persist($Pa);
                $EM->flush();
                $params = array("id" => $Pa->getId(),"actuacion" => "INSERT");
                return $this->redirectToRoute("replicaPa", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA PUNTO ASISTENCIAL CON ESTE CÓDIGO: " . $Pa->getPa();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPa");
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("queryPa");
            }
        }

        $params = array("pa" => $Pa,
            "form" => $form->createView());
        return $this->render("pa/add.html.twig", $params);
    }

    public function replicaAction($id,$actuacion) {

        $entityManager = $this->getDoctrine()->getManager();
        $Pa_repo = $entityManager->getRepository("AppBundle:Pa");
        $Pa = $Pa_repo->find($id);

        $resultado = $this->replicaPa($Pa,$actuacion);
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("pa/finProceso.html.twig", $params);
    }

    public function replicaPa($Pa, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/actualizacionPa.php ".$modo . " ".$Pa->getPa() . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionPa.php ".$modo . " ".$Pa->getPa() . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ReplicaUnidadFuncional');

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }

    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(PaDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('pa/query.html.twig', array(
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
