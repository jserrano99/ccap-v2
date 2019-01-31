<?php

/**
 * Description of Responsable Unidad de Unidad Organizativa
 *
 * @author jluis
 */

namespace CostesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdscripcionPlaza
 *
 * @ORM\Table(name="ccap_plaza_unidad")
 * @ORM\Entity(repositoryClass="CostesBundle\Repository\AdscripcionPlazaRepository")
 *
 */
class AdscripcionPlaza {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

	/**
	 * @var \CostesBundle\Entity\UnidadOrganizativa
	 *
	 * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\UnidadOrganizativa")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="unidad_organizativa_id", referencedColumnName="id")
	 * })
	 */
	private $unidadOrganizativa;

	/**
     * @var \CostesBundle\Entity\Plaza
     *
     * @ORM\ManyToOne(targetEntity="CostesBundle\Entity\Plaza")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plaza_id", referencedColumnName="id")
     * })
     */
    private $plaza;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="f_inicio", type="date", nullable=false)
     */
    private $fInicio;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="f_fin", type="date", nullable=true)
	 */
	private $fFin;



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
     * Set fInicio.
     *
     * @param \DateTime $fInicio
     *
     * @return AdscripcionPlaza
     */
    public function setFInicio($fInicio)
    {
        $this->fInicio = $fInicio;

        return $this;
    }

    /**
     * Get fInicio.
     *
     * @return \DateTime
     */
    public function getFInicio()
    {
        return $this->fInicio;
    }

    /**
     * Set fFin.
     *
     * @param \DateTime $fFin
     *
     * @return AdscripcionPlaza
     */
    public function setFFin($fFin = null)
    {
        $this->fFin = $fFin;

        return $this;
    }

    /**
     * Get fFin.
     *
     * @return \DateTime|null
     */
    public function getFFin()
    {
        return $this->fFin;
    }

    /**
     * Set unidadOrganizativa.
     *
     * @param \CostesBundle\Entity\UnidadOrganizativa|null $unidadOrganizativa
     *
     * @return AdscripcionPlaza
     */
    public function setUnidadOrganizativa(UnidadOrganizativa $unidadOrganizativa = null)
    {
        $this->unidadOrganizativa = $unidadOrganizativa;

        return $this;
    }

    /**
     * Get unidadOrganizativa.
     *
     * @return \CostesBundle\Entity\UnidadOrganizativa|null
     */
    public function getUnidadOrganizativa()
    {
        return $this->unidadOrganizativa;
    }

    /**
     * Set plaza.
     *
     * @param \CostesBundle\Entity\Plaza|null $plaza
     *
     * @return AdscripcionPlaza
     */
    public function setPlaza(Plaza $plaza = null)
    {
        $this->plaza = $plaza;

        return $this;
    }

    /**
     * Get plaza.
     *
     * @return \CostesBundle\Entity\Plaza|null
     */
    public function getPlaza()
    {
        return $this->plaza;
    }
}
