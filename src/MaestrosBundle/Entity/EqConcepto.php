<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqConcepto
 *
 * @ORM\Table(name="gums_eq_conceptos" 
 *           )
 * @ORM\Entity
 */
class EqConcepto {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var codigoLoc
     *
     * @ORM\Column(name="codigo_loc", type="string", length=3, nullable=false)
     */
    private $codigoLoc;

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
     * @var Concepto|null
     *
     * @ORM\ManyToOne(targetEntity="Concepto")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concepto_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $concepto;

    
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
     * @return EqConcepto
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
     * @return EqConcepto
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
     * @return EqConcepto
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
     * Set concepto.
     *
     * @param \MaestrosBundle\Entity\Concepto|null $concepto
     *
     * @return EqConcepto
     */
    public function setConcepto(\MaestrosBundle\Entity\Concepto $concepto = null)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto.
     *
     * @return \MaestrosBundle\Entity\Concepto|null
     */
    public function getConcepto()
    {
        return $this->concepto;
    }
}
