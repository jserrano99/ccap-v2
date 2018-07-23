<?php

/**
 * Description of Edificio
 *
 * @author jluis
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Edificio
 *
 * @ORM\Table(name="ccap_edificio", 
 
 *           )
 * @ORM\Entity
 */
class Edificio {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    
    /**
     * @var codigo
     *
     * @ORM\Column(name="codigo", type="string", length=2, nullable=false)
     */
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    
    public function getId() {
        return $this->id;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
        return $this;
    }


    public function __toString() {
        return $this->descripcion;
    }

}
