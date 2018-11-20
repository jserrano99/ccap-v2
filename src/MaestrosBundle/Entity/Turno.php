<?php

namespace MaestrosBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Turno
 *
 * @ORM\Table(name="gums_turno"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="MaestrosBundle\Repository\TurnoRepository")
 */

class Turno {
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
     * @ORM\Column(name="codigo", type="string", length=3, nullable=false)
     */
    private $codigo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */
    private $descripcion;


	/**
	 * @var time
	 *
	 * @ORM\Column(name="hini", type="time", nullable=true)
	 */
	private $hini;
	/**
	 * @var time
	 *
	 * @ORM\Column(name="hfin", type="time", nullable=true)
	 */
	private $hfin;

	/**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return Turno
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Turno
     */
    public function setDescripcion($descripcion = null)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    
    public function __toString() {
        return $this->descripcion;
    }

    /**
     * Set hini.
     *
     * @param \Time|null $hini
     *
     * @return Turno
     */
    public function setHini($hini = null)
    {
        $this->hini = $hini;

        return $this;
    }

    /**
     * Get hfin.
     *
     * @return \DateTime|null
     */
    public function getHfin()
    {
        return $this->hfin;
    }
}
