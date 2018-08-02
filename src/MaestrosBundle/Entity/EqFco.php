<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqFco
 *
 * @ORM\Table(name="gums_eq_fco" 
 *           )
 * @ORM\Entity
 */
class EqFco {

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
     * @var Fco|null
     *
     * @ORM\ManyToOne(targetEntity="Fco")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fco_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $fco;

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
     * @return EqFco
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
     * @return EqFco
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
     * @return EqFco
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
     * Set fco.
     *
     * @param \MaestrosBundle\Entity\Fco|null $fco
     *
     * @return EqFco
     */
    public function setFco(\MaestrosBundle\Entity\Fco $fco = null)
    {
        $this->fco = $fco;

        return $this;
    }

    /**
     * Get fco.
     *
     * @return \MaestrosBundle\Entity\Fco|null
     */
    public function getFco()
    {
        return $this->fco;
    }
}
