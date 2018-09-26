<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqGrupoCobro
 *
 * @ORM\Table(name="gums_eq_grc" 
 *           )
 * @ORM\Entity
 */
class EqGrupoCobro {

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
     * @var GrupoCobro|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoCobro")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupocobro_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $grupoCobro;

    
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
     * @return EqGrupoCobro
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
     * @return EqGrupoCobro
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
     * Set grupoCobro.
     *
     * @param \MaestrosBundle\Entity\GrupoCobro|null $grupoCobro
     *
     * @return EqGrupoCobro
     */
    public function setGrupoCobro(\MaestrosBundle\Entity\GrupoCobro $grupoCobro = null)
    {
        $this->grupoCobro = $grupoCobro;

        return $this;
    }

    /**
     * Get grupoCobro.
     *
     * @return \MaestrosBundle\Entity\GrupoCobro|null
     */
    public function getGrupoCobro()
    {
        return $this->grupoCobro;
    }

    /**
     * Set enUso.
     *
     * @param string $enUso
     *
     * @return EqGrupoCobro
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
