<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SyncroController extends Controller {

    public function lanzaAction() {
        return $this->render('syncro/lanzaSyncro.html.twig');
    }

    public function catGenAction() {
        $resultado = $this->syncroCatGen();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function catFpAction() {
        $resultado = $this->syncroCatFp();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function paAction() {
        $resultado = $this->syncroPa();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function cecosAction() {

        $resultado = $this->syncroCecos();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function cecociasAction() {
        $resultado = $this->syncroCecoCias();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function ufAction() {
        $resultado = $this->syncroUf();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function plazasAction() {
        $resultado = $this->syncroPlazas();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function categAction() {
        $resultado = $this->syncroCateg();
        $params = array("error" => $resultado["error"],
            "log" => $resultado["log"]);
        return $this->render('syncro/syncroOk.html.twig', $params);
    }

    public function syncroCatFp() {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroCatFp.php " . $modo;
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroCatFp");
        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }
        return $resultado;
    }

    public function syncroCatGen() {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroCatGen.php " . $modo;
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroCatGen");
        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }
        return $resultado;
    }

    public function syncroCateg() {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroCateg.php " . $modo;
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroCateg");
        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }
        return $resultado;
    }

    public function syncroPa() {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroPa.php " . $modo;
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroPA");

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }

    public function syncroCecoCias() {
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroCECOCIAS");

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroCecoCias.php " . $modo;
        $ServicioLog->setMensaje($php_script);
        $ServicioLog->escribeLog();
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }

    public function syncroCecos() {
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroCECOS");

        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');

        $php_script = "php " . $root . "/scripts/syncroCecos.php " . $modo;
        $ServicioLog->setMensaje($php_script);
        $ServicioLog->escribeLog();
        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }

    
    public function syncroUf() {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroUf.php " . $modo;

        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroUF");

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }

    
    public function syncroPlazas() {
        $root = $this->get('kernel')->getRootDir();
        $modo = $this->getParameter('modo');
        $php_script = "php " . $root . "/scripts/syncroPlazas.php " . $modo;

        $mensaje = exec($php_script, $SALIDA, $valor);
        $resultado["error"] = $valor;
        $resultado["log"] = $SALIDA;
        $ServicioLog = $this->get('app.escribelog');
        $ServicioLog->setLogger("syncroPLAZAS");

        foreach ($resultado["log"] as $linea) {
            $ServicioLog->setMensaje($linea);
            $ServicioLog->escribeLog();
        }

        return $resultado;
    }

}
