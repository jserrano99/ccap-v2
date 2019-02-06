<?php

/**
 * Description of TipoUnidad
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoUnidad
 *
 * @ORM\Table(name="ccap_tipo_unidad")
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\TipoUnidadRepository")
 */
class TipoUnidad {

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
	 * @ORM\Column(name="rol_jano", type="string", length=1, nullable=true)
	 */
	private $rolJano;


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
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return TipoUnidad
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

    /**
     * Set rolJano.
     *
     * @param string|null $rolJano
     *
     * @return TipoUnidad
     */
    public function setRolJano($rolJano = null)
    {
        $this->rolJano = $rolJano;

        return $this;
    }

    /**
     * Get rolJano.
     *
     * @return string|null
     */
    public function getRolJano()
    {
        return $this->rolJano;
    }

	/**
	 * @return string
	 */
    public function __toString()
    {
	 return $this->descripcion;
    }
}
