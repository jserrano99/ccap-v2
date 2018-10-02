<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqModalidad
 *
 * @ORM\Table(name="gums_eq_moa" 
 *           )
 * @ORM\Entity
 */
class EqModalidad {

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
     * @var Modalidad|null
     *
     * @ORM\ManyToOne(targetEntity="Modalidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="moa_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $modalidad;

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
     * @return EqModalidad
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
     * @return EqModalidad
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
     * @return EqModalidad
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
     * Set modalidad.
     *
     * @param \MaestrosBundle\Entity\Modalidad|null $modalidad
     *
     * @return EqModalidad
     */
    public function setModalidad(\MaestrosBundle\Entity\Modalidad $modalidad = null)
    {
        $this->modalidad = $modalidad;

        return $this;
    }

    /**
     * Get modalidad.
     *
     * @return \MaestrosBundle\Entity\Modalidad|null
     */
    public function getModalidad()
    {
        return $this->modalidad;
    }
}
