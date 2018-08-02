<?php

namespace AppBundle\Servicios;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Description of EscribeLog
 *
 * @author jluis_local
 */
class EscribeLog {

    //put your code here

    private $logger;
    private $mensaje;
    private $repo;
    private $filename;

    public function escribeLog($ficheroLog) {

        $ficheroLog = 'logs/'.$ficheroLog;
        
        $this->repo = new RotatingFileHandler($ficheroLog, 30,Logger::INFO);
        $this->filename = $this->repo->getUrl();
        $log = new Logger($this->logger);
        $log->pushHandler($this->repo);
        $log->info($this->mensaje);

        return true;
    }

    public function getLogger() {
        return $this->logger;
    }

    public function getRepo() {
        return $this->repo;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getMensaje() {
        return $this->mensaje;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
        return $this;
    }

    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
        return $this;
    }

}
