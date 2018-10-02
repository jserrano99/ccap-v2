<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqCatAnexo
 *
 * @ORM\Table(name="gums_eq_catanexo" 
 *           )
 * @ORM\Entity
 */
class EqCatAnexo {

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
     * @ORM\Column(name="codigo_loc", type="string", length=2, nullable=false)
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
     * @var CatAnexo|null
     *
     * @ORM\ManyToOne(targetEntity="CatAnexo")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="catanexo_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $catAnexo;

    
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
     * @return EqCatAnexo
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
     * @return EqCatAnexo
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
     * Set catAnexo.
     *
     * @param \MaestrosBundle\Entity\CatAnexo|null $catAnexo
     *
     * @return EqCatAnexo
     */
    public function setCatAnexo(\MaestrosBundle\Entity\CatAnexo $catAnexo = null)
    {
        $this->catAnexo = $catAnexo;

        return $this;
    }

    /**
     * Get catAnexo.
     *
     * @return \MaestrosBundle\Entity\CatAnexo|null
     */
    public function getCatAnexo()
    {
        return $this->catAnexo;
    }

    /**
     * Set enuso.
     *
     * @param string $enuso
     *
     * @return EqCatAnexo
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
