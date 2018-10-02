<?php

/**
 * Description of EqBajas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Edificio
 *
 * @ORM\Table(name="gums_eq_bajas" 
 *           )
 * @ORM\Entity
 */
class EqBajas {

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
     * @var Bajas|null
     *
     * @ORM\ManyToOne(targetEntity="Bajas")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bajas_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $bajas;
    
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
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigoLoc.
     *
     * @param string $codigoLoc
     *
     * @return EqBajas
     */
    public function setCodigoLoc($codigoLoc) {
        $this->codigoLoc = $codigoLoc;

        return $this;
    }

    /**
     * Get codigoLoc.
     *
     * @return string
     */
    public function getCodigoLoc() {
        return $this->codigoLoc;
    }

    /**
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqBajas
     */
    public function setEdificio(\ComunBundle\Entity\Edificio $edificio = null) {
        $this->edificio = $edificio;

        return $this;
    }

    /**
     * Get edificio.
     *
     * @return \ComunBundle\Entity\Edificio|null
     */
    public function getEdificio() {
        return $this->edificio;
    }

    /**
     * Set bajas.
     *
     * @param \MaestrosBundle\Entity\Bajas|null $bajas
     *
     * @return EqBajas
     */
    public function setBajas(\MaestrosBundle\Entity\Bajas $bajas = null) {
        $this->bajas = $bajas;

        return $this;
    }

    /**
     * Get bajas.
     *
     * @return \MaestrosBundle\Entity\Bajas|null
     */
    public function getBajas() {
        return $this->bajas;
    }


    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return EqBajas
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
}
