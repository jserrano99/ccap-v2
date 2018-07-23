<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use AppBundle\Datatables\CatGenDatatable;
use AppBundle\Form\CatGenType;

class CatGenController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }
    
    public function queryAction(Request $request) {
        $isAjax = $request->isXmlHttpRequest();

        $datatable = $this->get('sg_datatables.factory')->create(CatGenDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            
            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
            $datatableQueryBuilder->buildQuery();

            return $responseService->getResponse();
        }

        return $this->render('catgen/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $CatGen_repo = $em->getRepository("AppBundle:CatGen");
        $CatGen = $CatGen_repo->find($id);

        $form = $this->createForm(CatGenType::class, $CatGen);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $em->persist($CatGen);
                $em->flush();
                $params = array("id" => $CatGen->getId(),"actuacion" => "UPDATE");
                return $this->redirectToRoute("replicaCatGen", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA CATEGORÍA GENERAL CON ESTE CÓDIGO: " . $CatGen->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("catGen" => $CatGen,
                        "form" => $form->createView(),
                        "accion" => "MODIFICACIÓN");
        return $this->render("catgen/edit.html.twig", $params);
    }

    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $CatGen_repo = $em->getRepository("AppBundle:CatGen");
        $CatGen = new \AppBundle\Entity\CatGen();

        $form = $this->createForm(CatGenType::class, $CatGen);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $em->persist($CatGen);
                $em->flush();
                $params = array("id" => $CatGen->getId(),"actuacion" => "INSERT");
                return $this->redirectToRoute("replicaCatGen", $params);
            } catch (UniqueConstraintViolationException $ex) {
                $status = " YA EXISTE UNA CATEGORÍA GENERAL CON ESTE CÓDIGO: " . $CatGen->getCodigo();
                $this->sesion->getFlashBag()->add("status", $status);
            } catch (Doctrine\DBAL\DBALException $ex) {
                $status = "ERROR GENERAL=" . $ex->getMessage();
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }

        $params = array("catGen" => $CatGen,
                        "form" => $form->createView(),
                        "accion" => "CREACIÓN");
        return $this->render("catgen/edit.html.twig", $params);
    }

    public function replicaAction($id,$actuacion) {

        $em = $this->getDoctrine()->getManager();
        $CatGen_repo = $em->getRepository("AppBundle:CatGen");
        $CatGen = $CatGen_repo->find($id);

        $resultado = $this->replicaCatGen($CatGen,$actuacion);
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("catgen/finProceso.html.twig", $params);
    }

    public function replicaCatGen($CatGen, $actuacion) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/actualizacionCatGen.php ".$modo . " ".$CatGen->getCodigo() . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionCatGen.php ".$modo . " ".$CatGen->getCodigo() . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ReplicaCategoriaGeneral');

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }
        return $resultado;
    }
    
}
