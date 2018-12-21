<?php

/**
 * Description of UnidadOrganizativa
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidadOrganizativa
 *
 * @ORM\Table(name="ccap_unidad_organizativa"
 *         ,uniqueConstraints={@ORM\UniqueConstraint(name="uk_codigo", columns={"codigo"})}
 *           )
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\UnidadOrganizativaRepository")
 */
class UnidadOrganizativa {

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
	 * @ORM\Column(name="codigo", type="string", length=255, nullable=false)
	 */
	private $codigo;

	/**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", length=11, nullable=true)
     */
    private $orden;

	/**
	 * @var Plaza
	 *
	 * @ORM\ManyToOne(targetEntity="Plaza")
	 *   @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
	 */
	private $responsable;

	/**
	 * @var \CostesBundle\Entity\TipoUnidad
	 *
	 * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\TipoUnidad")
	 *   @ORM\JoinColumn(name="tipo_unidad_id", referencedColumnName="id")
	 */
	private $tipoUnidad;


	/**
	 * @var \CostesBundle\Entity\UnidadOrganizativa
	 *
	 * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\UnidadOrganizativa")
	 *   @ORM\JoinColumn(name="dependencia_id", referencedColumnName="id")
	 */
	private $dependencia;

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
     * @return UnidadOrganizativa
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
     * @return UnidadOrganizativa
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
     * Set orden.
     *
     * @param int|null $orden
     *
     * @return UnidadOrganizativa
     */
	public function setOrden($orden = null)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden.
     *
     * @return int|null
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set responsable.
     *
     * @param \CostesBundle\Entity\Plaza|null $responsable
     *
     * @return UnidadOrganizativa
     */
    public function setResponsable(Plaza $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable.
     *
     * @return \CostesBundle\Entity\Plaza|null
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set tipoUnidad.
     *
     * @param \CostesBundle\Entity\TipoUnidad|null $tipoUnidad
     *
     * @return UnidadOrganizativa
     */
    public function setTipoUnidad(TipoUnidad $tipoUnidad = null)
    {
        $this->tipoUnidad = $tipoUnidad;

        return $this;
    }

    /**
     * Get tipoUnidad.
     *
     * @return \CostesBundle\Entity\TipoUnidad|null
     */
    public function getTipoUnidad()
    {
        return $this->tipoUnidad;
    }

    /**
     * Set dependencia.
     *
     * @param \CostesBundle\Entity\UnidadOrganizativa|null $dependencia
     *
     * @return UnidadOrganizativa
     */
    public function setDependencia(UnidadOrganizativa $dependencia = null)
    {
        $this->dependencia = $dependencia;

        return $this;
    }

    /**
     * Get dependencia.
     *
     * @return \CostesBundle\Entity\UnidadOrganizativa|null
     */
    public function getDependencia()
    {
        return $this->dependencia;
    }

	/**
	 * @return string
	 */
    public function __toString()
    {
	 return $this->descripcion;
    }
}
