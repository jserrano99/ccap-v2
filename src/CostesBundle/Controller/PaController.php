<?php

namespace CostesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use CostesBundle\Entity\Pa;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use CostesBundle\Datatables\PaDatatable;
use Symfony\Component\HttpFoundation\Response;

class PaController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function verPaAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $Pa_repo = $entityManager->getRepository("CostesBundle:Pa");
        $Pa = $Pa_repo->find($id);
        $params = array("pa" => $Pa);
        return $this->render("costes/pa/verPa.html.twig", $params);
    }

    public function editAction(Request $request, $id) {
        $EM = $this->getDoctrine()->getManager();
        $Pa_repo = $EM->getRepository("CostesBundle:Pa");
        $Pa = $Pa_repo->find($id);

        $form = $this->createForm(\CostesBundle\Form\PaType::class, $Pa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $EM->persist($Pa);
                $EM->flush();
                $params = array("id" => $Pa->getId(), "actuacion" => "UPDATE");
                return $this->redirectToRoute("sincroPa", $params);
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

        $params = array("pa" => $Pa, "accion" => "MODIFICACIÓN",
            "form" => $form->createView());
        return $this->render("costes/pa/edit.html.twig", $params);
    }

    public function deleteAction($id) {
        $EM = $this->getDoctrine()->getManager();
        $Pa_repo = $EM->getRepository("CostesBundle:Pa");
        $Pa = $Pa_repo->find($id);
        $Pa->setEnuso('N');

        $EM->persist($Pa);
        $EM->flush();
        $status = " Unidad Funcional quitada de uso Correctamente";
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("queryPa");
    }

    public function addAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();
        $Pa_repo = $EM->getRepository("CostesBundle:Pa");
        $Pa = new Pa();

        $form = $this->createForm(\CostesBundle\Form\PaType::class, $Pa);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $Pa->setPa(substr($Pa->getOficial(), 4, 6));
            try {
                $EM->persist($Pa);
                $EM->flush();
                $params = array("id" => $Pa->getId(), "actuacion" => "INSERT");
                return $this->redirectToRoute("sincroPa", $params);
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

        $params = array("pa" => $Pa, "accion" => "NUEVO",
            "form" => $form->createView());
        return $this->render("costes/pa/edit.html.twig", $params);
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

        return $this->render('costes/pa/query.html.twig', array(
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
        $Pa = $em->getRepository("CostesBundle:Pa")->find($id);

        $SincroLog = new \ComunBundle\Entity\SincroLog();
        $fechaProceso = new \DateTime();

        $SincroLog->setUsuario($Usuario);
        $SincroLog->setTabla("ccap_pf");
        $SincroLog->setIdElemento($id);
        $SincroLog->setFechaProceso($fechaProceso);
        $SincroLog->setEstado($Estado);
        $em->persist($SincroLog);

        $Pa->setSincroLog($SincroLog);
        $em->persist($Pa);
        $em->flush();

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php = $this->getParameter('php');
        $php_script = $php." " . $root . "/scripts/costes/actualizacionPa.php " . $modo . "  " . $Pa->getId() . " " . $actuacion;

        $mensaje = exec($php_script, $SALIDA, $resultado);
        if ($resultado == 0) {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(2);
        } else {
            $Estado = $em->getRepository("ComunBundle:EstadoCargaInicial")->find(3);
        }

        $ficheroLog = 'sincroPa-' . $Pa->getPa() . '.log';
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger('ccap_pa->codigo:' . $Pa->getPa());
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
        $Pa = $em->getRepository("CostesBundle:Pa")->find($id);
        $params = array("id" => $Pa->getSincroLog()->getId());
        return $this->redirectToRoute("descargaSincroLog", $params);
    }

}
