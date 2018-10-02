<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModoPago
 *
 * @ORM\Table(name="gums_modopago"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\ModoPagoRepository")
 */
class ModoPago {

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
     * @ORM\Column(name="codigo", type="string", length=1, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=25, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="modopago_mes", type="string", length=1, nullable=true)
     */
    private $modoPagoMes;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return ModoPago
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descrip
     *
     * @return ModoPago
     */
    public function setDescripcion($descripcion = null) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set modoPagoMes.
     *
     * @param string|null $modoPagoMes
     *
     * @return ModoPago
     */
    public function setModoPagoMes($modoPagoMes = null) {
        $this->modoPagoMes = $modoPagoMes;

        return $this;
    }

    /**
     * Get modoPagoMes.
     *
     * @return string|null
     */
    public function getModoPagoMes() {
        return $this->modoPagoMes;
    }

    public function __toString() {
        return $this->descripcion . ' (' . $this->codigo . ')';
    }

}
