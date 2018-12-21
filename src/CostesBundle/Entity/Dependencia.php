<?php

/**
 * Description of Dependencia
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dependencia
 *
 * @ORM\Table(name="ccap_dependencia")
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\DependenciaRepository")
 */
class Dependencia {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="orden", type="integer", length=11, nullable=true)
	 */
	private $orden;

	/**
	 * @var \CostesBundle\Entity\UnidadOrganizativa
	 *
	 * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\UnidadOrganizativa")
	 *   @ORM\JoinColumn(name="unidad_madre_id", referencedColumnName="id")
	 */
	private $unidadMadre;

	/**
	 * @var \CostesBundle\Entity\UnidadOrganizativa
	 *
	 * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\UnidadOrganizativa")
	 *   @ORM\JoinColumn(name="unidad_hija_id", referencedColumnName="id")
	 */
	private $unidadHija;




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
     * Set orden.
     *
     * @param int|null $orden
     *
     * @return Dependencia
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
     * Set unidadMadre.
     *
     * @param \CostesBundle\Entity\UnidadOrganizativa|null $unidadMadre
     *
     * @return Dependencia
     */
    public function setUnidadMadre(\CostesBundle\Entity\UnidadOrganizativa $unidadMadre = null)
    {
        $this->unidadMadre = $unidadMadre;

        return $this;
    }

    /**
     * Get unidadMadre.
     *
     * @return \CostesBundle\Entity\UnidadOrganizativa|null
     */
    public function getUnidadMadre()
    {
        return $this->unidadMadre;
    }

    /**
     * Set unidadHija.
     *
     * @param \CostesBundle\Entity\UnidadOrganizativa|null $unidadHija
     *
     * @return Dependencia
     */
    public function setUnidadHija(\CostesBundle\Entity\UnidadOrganizativa $unidadHija = null)
    {
        $this->unidadHija = $unidadHija;

        return $this;
    }

    /**
     * Get unidadHija.
     *
     * @return \CostesBundle\Entity\UnidadOrganizativa|null
     */
    public function getUnidadHija()
    {
        return $this->unidadHija;
    }
}
