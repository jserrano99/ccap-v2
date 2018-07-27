<?php

namespace MaestrosBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use UniqueConstraintViolationException;

class EqCategController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function addAction($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $EqCateg_repo = $entityManager->getRepository("MaestrosBundle:EqCateg");
        $EqCateg = $EqCateg_repo->find($id);
        $EqCateg->setEnUso('S');
        $entityManager->persist($EqCateg);
        $entityManager->flush();

        $resultado = $this->replicaCateg($EqCateg->getId(),$EqCateg->getEdificio()->getCodigo());
        $params = ["error" => $resultado["error"],
            "salida" => $resultado["log"]];

        return $this->render("categ/eqfinProceso.html.twig", $params);
    }

    public function replicaCateg($id, $edificio) {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        if ($modo == 'REAL') {
            $php_script = "php " . $root . "/scripts/creaCateg.php " . $modo . " " . $id . " " . $edificio;
        } else {
            $php_script = "php " . $root . "/scripts/creaCateg.php " . $modo . " " . $id . " " . $edificio;
        }
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        
        return $resultado;
    }

}
