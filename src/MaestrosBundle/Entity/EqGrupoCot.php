<?php

/**
 * Description of EqAltas (Equivalencias de Códigos de Alta) 
 *
 * @author jluis
 */

namespace MaestrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EqGrupoCot
 *
 * @ORM\Table(name="gums_eq_grupocot" 
 *           )
 * @ORM\Entity
 */
class EqGrupoCot {

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
     * @var GrupoCot|null
     *
     * @ORM\ManyToOne(targetEntity="GrupoCot")
     * * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupocot_id", referencedColumnName="id",onDelete="CASCADE")
     * })
     */
    private $crupoCot;

    
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
     * @return EqGrupoCot
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
     * @return EqGrupoCot
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
     * Set GrupoCot.
     *
     * @param \MaestrosBundle\Entity\GrupoCot|null $GrupoCot
     *
     * @return EqGrupoCot
     */
    public function setGrupoCot(\MaestrosBundle\Entity\GrupoCot $grupoCot = null)
    {
        $this->grupoCot = $grupoCot;

        return $this;
    }

    /**
     * Get GrupoCot.
     *
     * @return \MaestrosBundle\Entity\GrupoCot|null
     */
    public function getGrupoCot()
    {
        return $this->grupoCot;
    }
    
}
