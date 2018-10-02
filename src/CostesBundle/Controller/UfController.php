<?php

namespace CostesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use CostesBundle\Entity\Uf;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use CostesBundle\Datatables\UfDatatable;
use Symfony\Component\HttpFoundation\Response;

class UfController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function verUfAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Uf_repo = $entityManager->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($id);
        $params = array("uf" => $Uf);
        return $this->render("costes/uf/verUf.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($id);

        $form = $this->createForm(\CostesBundle\Form\UfType::class, $Uf);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Uf);
                $EM->flush();
                $params = array("id" => $Uf->getId(), "actuacion" => "UPDATE");
                return $this->redirectToRoute("sincroUf", $params);
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
            "accion" => "MODIFICACIÓN",
            "form" => $form->createView());
        return $this->render("costes/uf/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("CostesBundle:Uf");
        $Uf = $Uf_repo->find($id);
        $Uf->setEnuso('N');

        $EM->persist($Uf);
        $EM->flush();
        $status = " Unidad Funcional quitada de uso Correctamente";
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryUf");
    }

    public function addAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();
        $Uf_repo = $EM->getRepository("CostesBundle:Uf");
        $Uf = new Uf();

        $form = $this->createForm(\CostesBundle\Form\UfType::class, $Uf);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Uf);
                $EM->flush();
                $params = array("id" => $Uf->getId(), "actuacion" => "INSERT");
                return $this->redirectToRoute("sincroUf", $params);
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
        return $this->render("costes/uf/edit.html.twig", $params);
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

        return $this->render('costes/uf/query.html.twig', array(
                    'datatable' => $datatable,
        ));
    }

    public function ajaxCalculaCodigoAction($codigo) {
        $edificio = (int) substr($codigo, 2, 2);
        $areaZona = substr($codigo, 2, 4);


        $entityManager = $this->getDoctrine()->getManager();
        $Equivalencia_repo = $entityManager->getRepository("CostesBundle:Equivalencia");
        $EquivalenciaAll = $Equivalencia_repo->createQueryBuilder('u')
                        ->where('u.areaZona = :areaZona')
                        ->setParameter('areaZona', $areaZona)
                        ->getQuery()->getResult();
        if ($EquivalenciaAll) {
            $Equivalencia = $EquivalenciaAll[0];
            $codigo12 = $Equivalencia->getCodigo();
        } else {
            $codigo12 = 'XX';
        }

        $Edificio_repo = $entityManager->getRepository("ComunBundle:Edificio");
        $EdificioAll = $Edificio_repo->createQueryBuilder('u')
                        ->where("u.codigo = :codigo")
                        ->setParameter("codigo", $edificio)
                        ->getQuery()->getResult();
        IF ($EdificioAll == null) {
            $codigoSaint["codigo"] = "ERROR-";
            $codigoSaint["edificio"] = $edificio;
        } else {
            $Edificio = $EdificioAll[0];
            $codigoSaint["codigo"] = $codigo12 . substr($codigo, 6, 4);
            $codigoSaint["edificio"] = $Edificio->getId();
        }
        $response = new Response();
        $response->setContent(json_encode($codigoSaint));
        $response->headers->set("Content-type", "application/json");
        return $response;
    }

    public function sincroAction($id, $actuacion) {
        $em = $this->getDoctrine()->getManager();
        $usuario_id = $this->sesion->get('usuario_id');
        $Usuario = $em->getRepository("ComunBundle:Usuario")->find($usuario_id);
        $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(1);
        $Uf = $em->getRepository("CostesBundle:Uf")->find($id);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("ccap_uf");
        $SincroLog->setIdElemento($id);
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Uf->setSincroLog($SincroLog);
        $em->persist($Uf);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/costes/actualizacionUf.php " . $modo . "  " . $Uf->getId() . " " . $actuacion;

        $mensaje = exec($php_script, $SALIDA, $resultado);
        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroUf-' . $Uf->getUf() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ccap_uf->codigo:' . $Uf->getUf());
        foreach ($SALIDA as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog($ficheroLog);
        }
        $SincroLog->setScript($php_script);
        $SincroLog->setFicheroLog($ServicioLog->getFilename());
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);
        $em->flush();

        $params = array("SincroLog" => $SincroLog,
            "resultado" => $resultado);
        $view = $this->renderView("finSincro.html.twig", $params);

        $response = new Response($view);

        $response->headers->set('Content-Disposition', 'inline');
        $response->headers->set('Content-Type', 'text/html');
        $response->headers->set('target', '_blank');

        return $response;
    }

    public function descargaLogAction($id) {
        $em = $this->getDoctrine()->getManager();
        $Uf = $em->getRepository("CostesBundle:Uf")->find($id);
        $params = array("id" => $Uf->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

}
