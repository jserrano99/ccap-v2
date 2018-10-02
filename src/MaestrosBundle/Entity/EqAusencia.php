<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqAusencia
 *
 * @ORM\Table(name="gums_eq_ausencias" 
 *           )
 * @ORM\Entity
 */
class EqAusencia {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ComunBundle\Entity\Edificio|null
     *
     * @ORM\ManyToOne(targetEntity="ComunBundle\Entity\Edificio")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="edificio_id", referencedColumnName="id")
     * })
     */
    private $edificio;

    /**
     * @var codigoLoc
     *
     * @ORM\Column(name="codigo_loc", type="string", length=3, nullable=false)
     */
    private $codigoLoc;

    /**
     * @var Ausencia|null
     *
     * @ORM\ManyToOne(targetEntity="Ausencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ausencia_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $ausencia;

    /**
     * @var enuso
     * 
     * @ORM\Column(name="enuso",type="string",length=1,nullable=false)
     */
    private $enuso;


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
     * Set codigoLoc.
     *
     * @param string $codigoLoc
     *
     * @return EqAusencia
     */
    public function setCodigoLoc($codigoLoc)
    {
        $this->codigoLoc = $codigoLoc;

        return $this;
    }

    /**
     * Get codigoLoc.
     *
     * @return string
     */
    public function getCodigoLoc()
    {
        return $this->codigoLoc;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return EqAusencia
     */
    public function setEnuso($enuso)
    {
        $this->enuso = $enuso;

        return $this;
    }

    /**
     * Get enuso.
     *
     * @return string
     */
    public function getEnuso()
    {
        return $this->enuso;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqAusencia
     */
    public function setEdificio(\ComunBundle\Entity\Edificio $edificio = null)
    {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * Get edificio.
     *
     * @return \ComunBundle\Entity\Edificio|null
     */
    public function getEdificio()
    {
        return $this->edificio;
    }

    /**
     * Set ausencia.
     *
     * @param \MaestrosBundle\Entity\Ausencia|null $ausencia
     *
     * @return EqAusencia
     */
    public function setAusencia(\MaestrosBundle\Entity\Ausencia $ausencia = null)
    {
        $this->ausencia = $ausencia;

        return $this;
    }

    /**
     * Get ausencia.
     *
     * @return \MaestrosBundle\Entity\Ausencia|null
     */
    public function getAusencia()
    {
        return $this->ausencia;
    }
}
