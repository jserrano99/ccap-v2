<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqOcupacion
 *
 * @ORM\Table(name="gums_eq_ocupacion" 
 *           )
 * @ORM\Entity
 */
class EqOcupacion {

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
     * @ORM\Column(name="codigo_loc", type="string", length=1, nullable=false)
     */
    private $codigoLoc;

    /**
     * @var Ocupacion|null
     *
     * @ORM\ManyToOne(targetEntity="Ocupacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ocupacion_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $epiAcc;

    /**
     * @var enUso
     * 
     * @ORM\Column(name="enuso",type="string",length=1,nullable=false)
     */
    private $enUso;



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
     * @return EqOcupacion
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
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return EqOcupacion
     */
    public function setEnUso($enUso)
    {
        $this->enUso = $enUso;

        return $this;
    }

    /**
     * Get enUso.
     *
     * @return string
     */
    public function getEnUso()
    {
        return $this->enUso;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqOcupacion
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
     * Set epiAcc.
     *
     * @param \MaestrosBundle\Entity\Ocupacion|null $epiAcc
     *
     * @return EqOcupacion
     */
    public function setEpiAcc(\MaestrosBundle\Entity\Ocupacion $epiAcc = null)
    {
        $this->epiAcc = $epiAcc;

        return $this;
    }

    /**
     * Get epiAcc.
     *
     * @return \MaestrosBundle\Entity\Ocupacion|null
     */
    public function getEpiAcc()
    {
        return $this->epiAcc;
    }
}
