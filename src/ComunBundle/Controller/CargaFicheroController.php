<?php

namespace ComunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;

class CargaFicheroController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function queryAction() {
        $CargaFicheroAll = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaFichero")->findAll();

        return $this->render('comun/cargaFichero/query.html.twig', array(
                    'CargaFicheroAll' => $CargaFicheroAll,
        ));
    }

    
    public function descargaLogAction($id) {

        $CargaFichero = $this->getDoctrine()->getManager()->getRepository("ComunBundle:CargaFichero")->find($id);

        $filename = $CargaFichero->getFicherolog();

        $response = new Response();
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=1');
        $response->setContent(file_get_contents($filename));

        return $response;
    }

}
