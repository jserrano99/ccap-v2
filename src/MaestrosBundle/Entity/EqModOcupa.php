<?php

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqModOcupa
 *
 * @ORM\Table(name="gums_eq_modocupa" 
 *           )
 * @ORM\Entity
 */
class EqModOcupa {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Edificio|null
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
     * @var ModOcupa|null
     *
     * @ORM\ManyToOne(targetEntity="ModOcupa")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modocupa_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $modOcupa;
    
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
     * @return EqModOcupa
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
     * @return EqModOcupa
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
     * @return EqModOcupa
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
     * Set modOcupa.
     *
     * @param \MaestrosBundle\Entity\ModOcupa|null $modOcupa
     *
     * @return EqModOcupa
     */
    public function setModOcupa(\MaestrosBundle\Entity\ModOcupa $modOcupa = null)
    {
        $this->modOcupa = $modOcupa;

        return $this;
    }

    /**
     * Get modOcupa.
     *
     * @return \MaestrosBundle\Entity\ModOcupa|null
     */
    public function getModOcupa()
    {
        return $this->modOcupa;
    }
}
