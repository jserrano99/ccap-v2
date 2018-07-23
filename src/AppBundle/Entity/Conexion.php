<?php

/**
 * Description of Conexion
 *
 * @author jluis_local
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Usuario;

/**
 * Proceso
 *
 * @ORM\Table(name="ccap_conexion", indexes={@ORM\Index(name="idx_usuario", columns={"usuario_id"})})
 * @ORM\Entity
 */
class Conexion {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    public function getId() {
        return $this->id;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getFecha(): \DateTime {
        return $this->fecha;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    public function setFecha(\DateTime $fecha) {
        $this->fecha = $fecha;
        return $this;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
        return $this;
    }

}
