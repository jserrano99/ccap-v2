<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqCatGen
 *
 * @ORM\Table(name="gums_eq_catgen" 
 *           )
 * @ORM\Entity
 */
class EqCatGen {

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
     * @ORM\Column(name="codigo_loc", type="string", length=4, nullable=false)
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
     * @var CatGen|null
     *
     * @ORM\ManyToOne(targetEntity="CatGen")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="catgen_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $catgen;

    
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
     * @return EqCatGen
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
     * Set edificio.
     *
     * @param \ComunBundle\Entity\Edificio|null $edificio
     *
     * @return EqCatGen
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
     * Set catgen.
     *
     * @param \MaestrosBundle\Entity\CatGen|null $catgen
     *
     * @return EqCatGen
     */
    public function setCatGen(\MaestrosBundle\Entity\CatGen $catgen = null)
    {
        $this->catgen = $catgen;

        return $this;
    }

    /**
     * Get catgen.
     *
     * @return \MaestrosBundle\Entity\CatGen|null
     */
    public function getCatGen()
    {
        return $this->catgen;
    }

    /**
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return EqCatGen
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
}
