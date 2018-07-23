<?php

namespace AppBundle\Controller;

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

        $datatable = $this->get('sg_datatables.factory')->create(\AppBundle\Datatables\CategDatatable::class);
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

    public function editAction(Request $request, $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Categ_repo = $entityManager->getRepository("AppBundle:Categ");
        $Categ = $Categ_repo->find($id);

        $form = $this->createForm(\AppBundle\Form\CategType::class, $Categ);
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

    public function addAction(Request $request) {
        
        
        $entityManager = $this->getDoctrine()->getManager();
        $Categ_repo = $entityManager->getRepository("AppBundle:Categ");
        $Categ = new \AppBundle\Entity\Categ();

        $form = $this->createForm(\AppBundle\Form\CategType::class, $Categ);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $entityManager->persist($Categ);
                $entityManager->flush();
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

    public function replicaAction($id,$actuacion) {

        $entityManager = $this->getDoctrine()->getManager();
        $Categ_repo = $entityManager->getRepository("AppBundle:Categ");
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
            $php_script = "php " . $root . "/scripts/actualizacionCateg.php ".$modo . " ".$Categ->getId() . " " . $actuacion;
        } else {
            $php_script = "php " . $root . "/scripts/actualizacionCateg.php ".$modo . " ".$Categ->getId() . " " . $actuacion;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ReplicaCategoriaProfesional');

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }
    
    public function ajaxCalculaCodigoAction($catgen_id) {

        $em = $this->getDoctrine()->getManager();
        $CatGen_repo = $em->getRepository("AppBundle:CatGen");
        $CatGen = $CatGen_repo->find($catgen_id);
        $Categ_repo = $em->getRepository("AppBundle:Categ");
        $UltimaCateg = $Categ_repo->createQueryBuilder('u')
                    ->select('max(u.codigo) as codigo')
                    ->where('u.catGen = :catgen')
                    ->setParameter('catgen',$CatGen)
                    ->getQuery()->getResult();
        
        $ultimoCodigo= $UltimaCateg[0]["codigo"];
        $codigo["codigo"] =$CatGen->getCodigo().sprintf('%02d', substr($ultimoCodigo,2,2)+1) ;
        
        $response = new Response();
        $response->setContent(json_encode($codigo));
        $response->headers->set("Content-type", "application/json");
        return $response;


    }
}
