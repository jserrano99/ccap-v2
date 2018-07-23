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

    public function escribeLog() {

        $FicheroLog = 'logs/ccap.log';
        $repo = new RotatingFileHandler($FicheroLog, 30, Logger::INFO);

        $log = new Logger($this->logger);
        $log->pushHandler($repo);
        $log->info($this->mensaje);

        return true;
    }

    public function getLogger() {
        return $this->logger;
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
