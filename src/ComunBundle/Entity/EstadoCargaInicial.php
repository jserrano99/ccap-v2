<?php

namespace ComunBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoCargaInicial
 *
 * @ORM\Table(name="comun_estado_carga_inicial", 
 *           )
 * @ORM\Entity
 */
class EstadoCargaInicial {

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
     * @ORM\Column(name="descripcion", type="string",length=255, nullable=false)
     */
    private $descripcion;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return EstadoCargaInicial
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function __toString() {
        return $this->descripcion;
    }

}
