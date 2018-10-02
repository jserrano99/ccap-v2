<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqCatFp
 *
 * @ORM\Table(name="gums_eq_catfp" 
 *           )
 * @ORM\Entity
 */
class EqCatFp {

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
     * @var CatFp|null
     *
     * @ORM\ManyToOne(targetEntity="CatFp")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="catfp_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $catfp;

    
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
     * @return EqCatFp
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
     * @return EqCatFp
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
     * Set catfp.
     *
     * @param \MaestrosBundle\Entity\CatFp|null $catfp
     *
     * @return EqCatFp
     */
    public function setCatFp(\MaestrosBundle\Entity\CatFp $catfp = null)
    {
        $this->catfp = $catfp;

        return $this;
    }

    /**
     * Get catfp.
     *
     * @return \MaestrosBundle\Entity\CatFp|null
     */
    public function getCatFp()
    {
        return $this->catfp;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return EqCatFp
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
